<?php
declare(strict_types=1);

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PackageGenCommand extends Command
{
    const DOC_DOCUMENT          = 'Document';
    const DOC_EMBEDDED_DOCUMENT = 'EmbeddedDocument';

    const TYPE_COLLECTION = "collection";
    const TYPE_EMBED_ONE  = 'EmbedOne';
    const TYPE_EMBED_MANY = 'EmbedMany';
    const TYPE_HASH       = 'hash';
    const TYPE_STRING     = 'string';

    /** @var \Twig\Environment */
    private $twig;

    public function __construct(\Twig\Environment $twig, ?string $name = null)
    {
        $this->twig = $twig;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('package:generate:package')
            ->setDefinition(
                [
                    new InputOption(
                        'file',
                        'f',
                        InputOption::VALUE_REQUIRED,
                        'Path to package json file',
                        'packages.json'
                    ),
                    new InputOption(
                        'dry-run',
                        null,
                        InputOption::VALUE_NONE,
                        'show preliminary data, do not generate documents'
                    ),
                ]
            )
            ->setDescription(
                'Generates mongo documents based on json file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $jsonFile = $input->getOption('file');
        if (!is_readable($jsonFile)) {
            throw new \InvalidArgumentException('File is not readable');
        }

        $output->writeln("reading file: " . $jsonFile);

        $jsonData = file_get_contents(__DIR__ . '/../../' . $jsonFile);
        $data = json_decode($jsonData, true);
        if (!$data or !array_key_exists('packages', $data)) {
            throw new \UnexpectedValueException('Json file is not in expected format');
        }

        $document = [];
        foreach ($data['packages'] as $name => $tags) {
            foreach ($tags as $ver => $package) {
                $tmpDoc = $this->processObject('package', $package);
                foreach ($tmpDoc as $n => $d) {
                    $document[$n] = array_key_exists($n, $document)
                        ? array_merge($document[$n], $d)
                        : $d;
                }
            }
        }

        if ($input->getOption('dry-run')) {
            dump($document);

            return;
        }

        $this->generateDocuments($document, $output);
    }

    protected function processObject(string $name, array $package): array
    {
        $hashes = ['require', 'require_dev', 'conflict', 'provide', 'psr_0', 'psr_4', 'replace', 'suggest'];

        $objects = [];
        $embedded = [];
        foreach ($package as $f => $data) {
            $f = lcfirst(str_replace(['_', '-'], ['__', '_'], $f));

            if (is_array($data)) {
                $key = key($data);

                $isHash = in_array($f, $hashes);
                // array
                if (is_int($key) or ctype_digit($key) or $isHash) {
                    $sample = reset($data);

                    if ($isHash) {
                        $objects[$name][$f] = self::TYPE_HASH;
                    }
                    else if (is_array($sample)) {
                        $embedded[$f] = $sample;
                        $objects[$name][$f] = self::TYPE_EMBED_MANY;
                    }
                    else {
                        $objects[$name][$f] = self::TYPE_COLLECTION;
                    }
                }
                // object
                else {
                    $embedded[$f] = $data;
                    $objects[$name][$f] = self::TYPE_EMBED_ONE;
                }
            }
            else {
                $objects[$name][$f] = self::TYPE_STRING;
            }
        }

        foreach ($embedded as $f => $data) {
            if (is_int($f)) {
                throw new \UnexpectedValueException('Embedded objects cannot contain numeric keys');
            }

            $objects = array_merge($objects, $this->processObject($f, $data));
        }

        return $objects;
    }

    protected function generateDocuments(array $documents, OutputInterface $output): void
    {
        foreach ($documents as $name => $properties) {
            if ($name == 'package') {
                $docData = [
                    'name' => $name,
                    'DocumentType' => self::DOC_DOCUMENT,
                    'properties' => $properties,
                ];
            }
            else {
                $docData = [
                    'name' => $name,
                    'DocumentType' => self::DOC_EMBEDDED_DOCUMENT,
                    'properties' => $properties,
                ];
            }

            dump($docData);

            $document = $this->twig->render('document/base.php.twig', $docData);

            $docPath = __DIR__ . '/../Document/' . ucfirst($name) . '.php';
            if (is_readable($docPath)) {
                copy($docPath, $docPath . '.bak');
                unlink($docPath);
                $output->writeln('Previous document backup: ' . $docPath . '.bak');
            }

            if (!file_put_contents($docPath, $document)) {
                throw new \RuntimeException('Failed to create document in: ' . $docPath);
            }

            $output->writeln('New document: ' . $docPath);
        }
    }
}

