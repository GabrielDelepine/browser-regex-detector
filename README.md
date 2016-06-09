What's this project ?
======================
A PHP script who analyse the user agent and define the browser name, the browser version and the plateform.


Why should I use this script ?
======================
To save execution time (the script takes less than 1ms) ! It's faster to make a regex on a string than use a "database" like the browscap.ini file.


Demo
======================
You can try the script in <a href="http://gabrieldelepine.github.io/browser-regex-detector/">the demo page</a>.
If you find a user agent who is not correctly detected please open an issue.


Limit of the concept
======================
This script will only give you few information about the browser. If you need to know more like browser capabilities, you should check for another project like the <a href="https://github.com/GaretJax/phpbrowscap">Browscap project</a> for example.


Sources
======================
I found a pice of code <a href="http://www.silverphp.com/how-to-get-browser-and-operating-system-information-with-php.html">here</a> and <a href="http://www.kingofdevelopers.com/php-classes/get-browser-name-version.php">here</a>.
I don't know who wrote-it first.


Future improvement
======================
- Detect the device type (Desktop|Tablet|Mobile)
- Demo page to try with different user agent
- ini file to define if a browser is supported or not by your application
- Version of the plateform (ex : XP / w7 / W8 / ...)
