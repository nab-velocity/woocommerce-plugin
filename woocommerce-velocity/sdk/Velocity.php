<?php

abstract class VelocityCon {

  const VERSION = '1.0';

}
require_once 'configuration.php';
require_once 'Velocity/Helpers.php';
require_once 'Velocity/Errors.php';
require_once 'Velocity/XmlParser.php';
require_once 'Velocity/Message.php';
require_once 'Velocity/XmlCreator.php';
require_once 'Velocity/Connection.php';
require_once 'Velocity/Transaction.php';
require_once 'Velocity/Processor.php';

/* 
 * check php version if below 5.2.1 then throw exception msg.
 */
if (version_compare(PHP_VERSION, '5.2.1', '<')) {
  throw new Exception('PHP version >= 5.2.1 required');
}

/* 
 * check the dependency of curl, simplexml, openssl loaded or not.
 */
function checkDependencies(){
  $extensions = array('curl', 'SimpleXML', 'openssl');
  foreach ($extensions AS $ext) {
    if (!extension_loaded($ext)) {
      throw new Exception('Velocity-client-php requires the ' . $ext . ' extension.');
    }
  }
}

checkDependencies();

