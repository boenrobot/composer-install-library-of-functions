# composer-install-library-of-functions

A Composer plugin to handle the "functionmap" in "extra".

This property is an object where each key is the name of an autoload section (either ```autoload``` or ```autoload-dev```). Each value is an object. Within each object, each key is the fully qualified name of a function, and each value is the path to a file where this function is defined.

If the function exists already during an install/update (either because it is built in, or because it is declared during an auto_prepend_file PHP file), it won't be added to the autoloader. If it doesn't exist, it will be added to the autoloader "files" section.

Note also that a file already present in the "files" section will be removed from it if the function exists. This allows you to include all files in your files section, so that your package works with or without this plugin (but will be better optimized with this plugin).