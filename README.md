# Magento 2 Module: CronSemaphore

## Installation

Install the composer patch system:
    
    composer require cweagans/composer-patches:^1.6

Then into your compose.json file the following extra data:

    "extra": {
      "patches": {
        "websolute/m2if-wrapper": {
          "m2ifWrapper.patch ": "m2ifSetTokens.patch"
        }
      }
    }

## How to use

Just inject the following manager:

    \Websolute\M2ifWrapper\Model\Wrapper $wrapper

Then use the `execute(array $parameters, OutputInterface $output)` method to run m2if programmatically:

    // \Symfony\Component\Console\Output\Output $outputLogger
    $parameters = [
        '--source-dir=var/importexport',
        '--parameterX'
    ];

    $exitCode = $this->wrapper->execute($parameters, $outputLogger);
