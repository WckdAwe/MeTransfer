<?php

namespace Tres\PackageManager {
    
    class Autoload {
        
        /**
         * The root URI of all files.
         * 
         * @var string
         */
        protected $_rootURI = '';
        
        /**
         * The manifest data.
         * 
         * @var array
         */
        protected $_manifest = array();
        
        /**
         * The list of namespaces.
         * 
         * @var array
         */
        protected $_namespacePrefixes = array();
        
        /**
         * Sets the root URI.
         * 
         * @param string $rootURI The root URI of all files.
         */
        public function __construct($rootURI, array $manifest){
            $this->_rootURI = rtrim($rootURI, '/');
            $this->_manifest = $manifest;
            
            spl_autoload_register(array($this, '_loadClass'));
            
            $this->_registerNamespaces();
            $this->_registerAliases();
            $this->_registerFiles();
        }
        
        /**
         * Adds a namespace to let the autoloader know to look for it.
         * 
         * @param string $namespacePrefix A part or the complete namespace.
         * @param string $dir             The directory to look into.
         */
        public function addNamespace($namespacePrefix, $dir){
            $namespacePrefix = trim($namespacePrefix, '\\').'\\';
            $this->_namespacePrefixes[$namespacePrefix] = $dir;
        }
        
        /**
         * Gives a certain class an alias.
         * 
         * @param string $alias    The alias name for the class.
         * @param string $original The original class.
         * @param bool   $autoload Whether to autoload if the original class is not found.
         * 
         * @return bool  Returns true on success or false on failure.
         */
        public function setAlias($alias, $original, $autoload = true){
            return class_alias($original, $alias, $autoload);
        }
        
        /**
         * Loads the given file.
         * 
         * @param  string $file
         */
        public function loadFile($file){
            if(require_once($this->_rootURI.'/'.$file)){
                return true;
            }
            
            return false;
        }
        
        /**
         * Loads the given class.
         * 
         * @param  string $class The class which was being called.
         * @return bool          Whether it succeeded or not.
         */
        protected function _loadClass($class){
            $namespacePrefix = $class;
            
            while(false !== $pos = strrpos($namespacePrefix, '\\')){
                // Retains the trailing namespace separator in the prefix.
                $namespacePrefix = substr($class, 0, $pos + 1);
                
                // The rest is the class name.
                $className = substr($class, $pos + 1);
                
                // Tries to load a mapped file for the prefix and class name.
                $mappedFile = $this->_loadMappedFile($namespacePrefix, $className);
                
                if($mappedFile){
                    return $mappedFile;
                }
                
                // Removes the trailing namespace separator for the next iteration of strrpos().
                $namespacePrefix = rtrim($namespacePrefix, '\\');   
            }
            
            return false;
        }
        
        /**
         * Loads the class.
         * 
         * @param  string $namespacePrefix A part or the complete namespace.
         * @param  string $className       The class name.
         * @return bool                    Whether it succeeded or not.
         */
        protected function _loadMappedFile($namespacePrefix, $className){
            if(!isset($this->_namespacePrefixes[$namespacePrefix])){
                return false;
            }
            
            $file = $this->_namespacePrefixes[$namespacePrefix].'/'.$className;
            $file = str_replace('\\', '/', $file).'.class.php';
            
            return $this->loadFile($file);
        }
        
        /**
         * Registers the namespaces.
         */
        protected function _registerNamespaces(){
            foreach($this->_manifest['namespaces'] as $namespace => $dir){
                $this->addNamespace($namespace, $dir);
            }
        }
        
        /**
         * Registers the aliases.
         */
        protected function _registerAliases(){
            foreach($this->_manifest['aliases'] as $alias => $original){
                $this->setAlias($alias, $original);
            }
        }
        
        /**
         * Registers the files.
         */
        protected function _registerFiles(){
            foreach($this->_manifest['files'] as $file){
                $this->loadFile($file);
            }
        }
        
    }
    
}
