<?xml version="1.0" encoding="UTF-8"?>
<package packagerversion="1.4.1" version="2.0"
   xmlns="http://pear.php.net/dtd/package-2.0"
   xmlns:tasks="http://pear.php.net/dtd/tasks-1.0"
   xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
   xsi:schemaLocation="http://pear.php.net/dtd/tasks-1.0
   http://pear.php.net/dtd/tasks-1.0.xsd http://pear.php.net/dtd/package-2.0
   http://pear.php.net/dtd/package-2.0.xsd"
>

 <name>Mondongo</name>
 <channel>pear.mondongo.es</channel>
 <summary>MondonGO is a simple, powerful and ultrafast Object Document Mapper (ODM) for PHP and MongoDB.</summary>
 <description>MondonGO is a simple, powerful and ultrafast Object Document Mapper (ODM) for PHP and MongoDB.</description>
 <lead>
  <name>Pablo Díez</name>
  <user>pablodip</user>
  <email>pablodip@gmail.com</email>
  <active>yes</active>
 </lead>
 <date>##DATE##</date>
 <version>
   <release>##VERSION##</release>
   <api>##API_VERSION##</api>
 </version>
 <stability>
  <release>##STABILITY##</release>
  <api>##STABILITY##</api>
 </stability>
 <license uri="http://www.gnu.org/licenses/lgpl.html">LGPL</license>
 <notes>-</notes>

 <contents>
  <dir name="/">
   <file name="CHANGELOG" role="doc" />
   <file name="LICENSE" role="doc" />
   <file name="README.markdown" role="doc" />
    ##FILES##
  </dir>
 </contents>

 <dependencies>
  <required>
   <php>
    <min>5.3.0</min>
   </php>
   <pearinstaller>
    <min>1.4.1</min>
   </pearinstaller>
  </required>
 </dependencies>

 <phprelease>
  <filelist>
    ##FILELIST##
  </filelist>
 </phprelease>

</package>
