<?php
/**
 *	Rae
 *
 *	Rae ("Record-Analyze-Evolve") is a lightweight profiling and preformance analyzing library used to benchmark and analyze certain aspect of web based applications.
 *
 *	@author Robin Grass <robin@kodlabbet.net>
 *	@link http://kodlabbet.net
 *
 *	@license http://www.opensource.org/licenses/MIT MIT License
 */

/* @namespace Rae */
namespace Rae;

/**
 *	@const string RAE_ROOT_PATH
 */
define('RAE_ROOT_PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);

/**
 *	@const string RAE_LIBS_PATH
 */
define('RAE_LIBS_PATH', RAE_ROOT_PATH . 'libs' . DIRECTORY_SEPARATOR);

/**
 *	@const string RAE_ROOT_PATH
 */
define('RAE_ENABLED', true);

/**
 *	Bootstrap
 */
require_once RAE_ROOT_PATH . 'Functions.php';
require_once RAE_LIBS_PATH . 'Collection.php';
require_once RAE_LIBS_PATH . 'Record.php';

/**
 *	Register collector libraries
 */
require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'Benchmark.php';
\Rae\Benchmark::register();

require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'Memory.php';
\Rae\Memory::register();

require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'Console.php';
\Rae\Console::register();

require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'Constant.php';
\Rae\Constant::register();

require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'File.php';
\Rae\File::register();

require_once RAE_LIBS_PATH . 'Records' . DIRECTORY_SEPARATOR . 'Environment.php';
\Rae\Environment::register();
?>