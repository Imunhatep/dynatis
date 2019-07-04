<?php
declare(strict_types=1);

namespace App\Factory;


use App\Builder\ArchiveBuilder;
use App\Builder\PackagesBuilder;
use App\Builder\WebBuilder;
use App\Console\Application;
use App\Document\Requirement;
use App\Document\Repository;
use App\PackageSelection\PackageSelection;
use Composer\Composer;
use Composer\Config;
use Composer\Config\JsonConfigSource;
use Composer\Factory;
use Composer\IO\BufferIO;
use Composer\IO\ConsoleIO;
use Composer\IO\IOInterface;
use Composer\Json\JsonFile;
use Composer\Json\JsonValidationException;
use Composer\Util\ErrorHandler;
use Composer\Util\RemoteFilesystem;
use Doctrine\ODM\MongoDB\DocumentManager;
use JsonSchema\Validator;
use Seld\JsonLint\JsonParser;
use Seld\JsonLint\ParsingException;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Filesystem\Exception\IOException;

class ComposerFactory
{
    /** @var DocumentManager */
    private $documentManager;

    /** @var string */
    private $jsonSchemaPath;

    /** @var string */
    private $statisJsonPath;

    /** @var IOInterface */
    protected $io;

    public function __construct(string $projectDir, DocumentManager $dm)
    {
        $this->documentManager = $dm;

        $this->statisJsonPath = $projectDir . '/satis.json';
        $this->jsonSchemaPath = $projectDir . '/res/satis-schema.json';

        $styles = Factory::createAdditionalStyles();
        $formatter = new OutputFormatter;
        foreach ($styles as $name => $style) {
            $formatter->setStyle($name, $style);
        }

        $this->io = new BufferIO('', StreamOutput::VERBOSITY_NORMAL, $formatter);
        ErrorHandler::register($this->io);
    }

    /**
     * @throws JsonValidationException
     * @throws ParsingException
     */
    function getComposer(): Composer
    {
        // load auth.json authentication information and pass it to the io interface
        $config = $this->readConfigFile($this->statisJsonPath, true);

        // disable packagist by default
        unset(Config::$defaultRepositories['packagist'], Config::$defaultRepositories['packagist.org']);

        if (array_key_exists('output-dir', $config)) {
            throw new \InvalidArgumentException('The output dir must be configured inside: ' . $this->statisJsonPath);
        }

        try {
            $composer = Factory::create($this->io, $config);
        }
        catch (\InvalidArgumentException $e) {
            $this->io->write($e->getMessage());
            exit(1);
        }

        return $composer;
    }
    /**
     * @throws JsonValidationException
     * @throws ParsingException
     * @throws \Exception
     */
    protected function execute(): int
    {
        // load auth.json authentication information and pass it to the io interface
        $config = $this->readConfigFile($this->statisJsonPath);

        // disable packagist by default
        unset(Config::$defaultRepositories['packagist'], Config::$defaultRepositories['packagist.org']);

        if (array_key_exists('output-dir', $config)) {
            throw new \InvalidArgumentException('The output dir must be configured inside ' . $this->statisJsonPath);
        }

        /** @var Application $application */
        $application = $this->getApplication();

        /** @var Composer $composer */
        $composer = $application->getComposer(true, $config);


        $packageSelection = new PackageSelection($output, $config['output-dir'], $config, false);

        if (null !== $repositoryUrl) {
            $packageSelection->setRepositoryFilter($repositoryUrl, (bool)$input->getOption('repository-strict'));
        }
        else {
            $packageSelection->setPackagesFilter($packagesFilter);
        }

        $packages = $packageSelection->select($composer, $verbose);

        if (isset($config['archive']['directory'])) {
            $downloads = new ArchiveBuilder($output, $outputDir, $config, $skipErrors);
            $downloads->setComposer($composer);
            $downloads->setInput($input);
            $downloads->dump($packages);
        }

        $packages = $packageSelection->clean();

        if ($packageSelection->hasFilterForPackages() || $packageSelection->hasRepositoryFilter()) {
            // in case of an active filter we need to load the dumped packages.json and merge the
            // updated packages in
            $oldPackages = $packageSelection->load();
            $packages += $oldPackages;
            ksort($packages);
        }

        $packagesBuilder = new PackagesBuilder($output, $outputDir, $config, $skipErrors);
        $packagesBuilder->dump($packages);

        if ($htmlView = !$input->getOption('no-html-output')) {
            $htmlView = !isset($config['output-html']) || $config['output-html'];
        }

        if ($htmlView) {
            $web = new WebBuilder($output, $outputDir, $config, $skipErrors);
            $web->setRootPackage($composer->getPackage());
            $web->dump($packages);
        }

        return 0;
    }

    /**
     * @throws JsonValidationException
     * @throws ParsingException
     */
    private function readConfigFile(string $configFile): array
    {
        $this->check($configFile);

        // load auth.json authentication information and pass it to the io interface
        $io = $this->getIO();
        $io->loadConfiguration($this->getConfiguration());

        if (preg_match('{^https?://}i', $configFile)) {
            $rfs = new RemoteFilesystem($io);
            $contents = $rfs->getContents(parse_url($configFile, PHP_URL_HOST), $configFile, false);
            $config = JsonFile::parseJson($contents, $configFile);
        }
        else {
            $file = new JsonFile($configFile);

            if (!$file->exists()) {
                throw new IOException('<error>File not found: ' . $configFile . '</error>');
            }

            $config = $file->read();
        }

        if ($config) {
            /** @var Repository $repo */
            foreach ($this->documentManager->getRepository(Repository::class)->findAll() as $repo) {
                $config['repositories'][] = [
                    'type' => $repo->getType(),
                    'url' => $repo->getUrl(),
                ];
            }

            /** @var Requirement $package */
            foreach ($this->documentManager->getRepository(Requirement::class)->findAll() as $package) {
                $config['require'][$package->getNamespace()] = $package->getVersion();
            }
        }

        return (array)$config;
    }

    private function getConfiguration(): Config
    {
        $config = new Config();

        // add dir to the config
        $config->merge(['config' => ['home' => $this->getComposerHome()]]);

        // load global auth file
        $file = new JsonFile($config->get('home') . '/auth.json');
        if ($file->exists()) {
            $config->merge(['config' => $file->read()]);
        }
        $config->setAuthConfigSource(new JsonConfigSource($file, true));

        return $config;
    }

    private function getComposerHome(): string
    {
        $home = getenv('COMPOSER_HOME');
        if (!$home) {
            if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
                if (!getenv('APPDATA')) {
                    throw new \RuntimeException(
                        'The APPDATA or COMPOSER_HOME environment variable must be set for composer to run correctly'
                    );
                }
                $home = strtr(getenv('APPDATA'), '\\', '/') . '/Composer';
            }
            else {
                if (!getenv('HOME')) {
                    throw new \RuntimeException(
                        'The HOME or COMPOSER_HOME environment variable must be set for composer to run correctly'
                    );
                }
                $home = rtrim(getenv('HOME'), '/') . '/.composer';
            }
        }

        return $home;
    }

    /**
     * @throws ParsingException        if the json file has an invalid syntax
     * @throws JsonValidationException if the json file doesn't match the schema
     */
    private function check(string $configFile): bool
    {
        $content = file_get_contents($configFile);

        $parser = new JsonParser();
        $result = $parser->lint($content);

        if (null === $result) {
            if (defined('JSON_ERROR_UTF8') && JSON_ERROR_UTF8 === json_last_error()) {
                throw new \UnexpectedValueException('"' . $configFile . '" is not UTF-8, could not parse as JSON');
            }

            if (!is_readable($this->jsonSchemaPath)) {
                throw new IOException(
                    sprintf('Unable to read JSON schema from: %s', $this->jsonSchemaPath)
                );
            }

            $data = json_decode($content);
            $schema = json_decode(file_get_contents($this->jsonSchemaPath));

            $validator = new Validator();
            $validator->check($data, $schema);

            if (!$validator->isValid()) {
                $errors = [];
                foreach ((array)$validator->getErrors() as $error) {
                    $errors[] = ($error['property'] ? $error['property'] . ' : ' : '') . $error['message'];
                }

                throw new JsonValidationException(
                    'The json config file does not match the expected JSON schema',
                    $errors
                );
            }

            return true;
        }

        throw new ParsingException(
            sprintf('"%s" does not contain valid JSON' . "\n" . '%s', $configFile, $result->getMessage()),
            $result->getDetails()
        );
    }

    private function getIO(): BufferIO
    {
        return $this->io;
    }
}