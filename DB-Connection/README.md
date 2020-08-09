# PHP MySQLi Simple Class

MysqliDb -- Simple MySQLi php class
<hr>

### Table of Contents

**[Insert Query](#insert-query)**  
**[Update Query](#update-query)**  
**[Select Query](#select-query)**  
**[More details](#more-details)**

### Installation

run on command line

```cd yourproject```


```git clone https://github.com/viralc/DB.git```


```php
include(dirname(__FILE__).'/mysqli_class.php');
include(dirname(__FILE__).'/mysqli_conf.php');
```

### Config file
Edit mysqli_conf.php add your mysql details:
```php
define('HOST', 'localhost');
define('USERNAME', 'yourusername');
define('PASSWORD', 'password');
define('DBNAME', 'databasename');
define('DEBUG', true);
```

### Initialization
Simple initialization
```php
$db = new ViralDB();
$con = $db->con();
```

### Insert Query
Simple example
```php
$table_name = "table_name";
$name = "Robert";
$email = "Robert@test.com";
$data = array("name"=>$name,"email"=>$email);
$result = $db->insert($data,$table_name);
$db->close();
```


### Update Query
```php
$con = $db->con();
$table_name = "table_name";
$name = "John";
$email = "john@test.com";
$id = 22;
$sql = 'UPDATE '.$table_name.' SET name = \''.mysqli_real_escape_string($con, $name).'\', email = \''.mysqli_real_escape_string($con, $email).'\' WHERE id = '.$id.'';
$result = $db->update($sql);
$db->close();
```

### Select Query
```php
$sql = "SELECT * FROM table_name WHERE id = 1";
$result = $db->query($sql);
$db->close();
```

### More details
``` please see sample file```