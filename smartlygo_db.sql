-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 30, 2026 at 05:13 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartlygo_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'Admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int NOT NULL,
  `user_id` int DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('New','Read','Responded') DEFAULT 'New',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `user_id`, `name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 4, 'Jenish Gohel', 'jenishgohel11@gmail.com', 'Relevant to Games', 'Its Very Nice to learn Core PHP Concepts', 'New', '2025-12-15 12:13:53'),
(2, 9, 'Vipul Bambhaniya', 'vipul@gmail.com', 'Appreciation', 'This site is really good for learning.', 'New', '2026-01-08 19:38:37'),
(3, 3, 'Ravi Sav', 'ravi@gmail.com', 'Regarding Contact', 'NA', 'New', '2026-01-16 17:29:41');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL,
  `user_id` int DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rating` int NOT NULL,
  `category` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `status` enum('New','Reviewed','Resolved') DEFAULT 'New',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `name`, `email`, `rating`, `category`, `message`, `status`, `created_at`) VALUES
(2, 9, 'Vipul Bambhaniya', 'vipul@gmail.com', 5, 'Games', 'Appreciable', 'New', '2026-01-08 19:38:15'),
(3, 3, 'Jenish Gohel', 'jenishgohel@gmail.com', 4, 'Content', 'Keep It UP. Good Concept with attractive UI.', 'New', '2026-01-16 17:29:59'),
(4, 0, 'Harsh Dodiya', 'harsh@gmail.com', 5, 'Games', 'Very Nice Challenges for core concepts', 'New', '2026-01-17 11:57:24');

-- --------------------------------------------------------

--
-- Table structure for table `fillintheblanks`
--

CREATE TABLE `fillintheblanks` (
  `fib_id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fillintheblanks`
--

INSERT INTO `fillintheblanks` (`fib_id`, `question`, `correct_answer`) VALUES
(1, 'PHP code is executed on the ______ side.', 'server'),
(2, 'The PHP file extension is ______.', '.php'),
(3, '______ is used to output data in PHP.', 'echo'),
(4, 'To store a value in PHP, we use a ______.', 'variable'),
(5, 'All variables in PHP start with the ______ symbol.', '$'),
(6, 'The PHP function used to print formatted output is ______.', 'printf'),
(7, '______ is the correct way to write a PHP comment for a single line.', '//'),
(8, 'The PHP superglobal used to collect form data sent with POST method is ______.', '$_POST'),
(9, 'The PHP superglobal used to collect form data sent with GET method is ______.', '$_GET'),
(10, '______ function is used to find the length of a string in PHP.', 'strlen'),
(11, 'To connect PHP with MySQL, the ______ extension is commonly used.', 'mysqli'),
(12, 'The SQL command used to fetch data from a database is ______.', 'SELECT'),
(13, '______ function is used to include a PHP file and stop execution if it fails.', 'require'),
(14, 'The PHP conditional statement used for multiple conditions is ______.', 'if-else'),
(15, '______ loop is used when the number of iterations is known in advance.', 'for'),
(16, '______ loop executes at least once even if the condition is false.', 'do-while'),
(17, 'To check the data type of a variable in PHP, we use the ______ function.', 'gettype'),
(18, 'The PHP operator used to concatenate strings is ______.', '.'),
(19, '______ function is used to start a session in PHP.', 'session_start'),
(20, 'To destroy a session completely, we use ______.', 'session_destroy'),
(21, 'The PHP function used to redirect a user to another page is ______.', 'header'),
(22, '______ is used to store session data temporarily on the server.', 'session'),
(23, 'To upload files in PHP, the form method must be ______.', 'POST'),
(24, 'The PHP superglobal used for file uploads is ______.', '$_FILES'),
(25, '______ function is used to prevent SQL Injection in prepared statements.', 'bind_param'),
(26, 'The PHP function used to hash passwords securely is ______.', 'password_hash'),
(27, 'To verify a hashed password, we use ______.', 'password_verify'),
(28, '______ keyword is used to stop loop execution.', 'break'),
(29, 'The PHP keyword used to skip the current iteration is ______.', 'continue'),
(30, '______ function converts a string to lowercase in PHP.', 'strtolower'),
(31, '______ function removes whitespace from both ends of a string.', 'trim'),
(32, 'The PHP function used to get the current date is ______.', 'date'),
(33, '______ function is used to check whether a variable is set.', 'isset'),
(34, '______ function checks whether a variable is empty.', 'empty'),
(35, 'The PHP function used to send cookies to the browser is ______.', 'setcookie'),
(36, '______ superglobal stores server and execution environment information.', '$_SERVER'),
(37, 'The PHP function used to read data from a file is ______.', 'fread'),
(38, '______ function opens a file in PHP.', 'fopen'),
(39, 'To close an opened file, we use ______.', 'fclose'),
(40, 'The PHP operator used for comparison is ______.', '=='),
(41, '______ operator checks both value and data type.', '==='),
(42, 'The PHP function used to count array elements is ______.', 'count'),
(43, '______ array stores multiple values in a single variable.', 'indexed'),
(44, 'The PHP array that uses named keys is called ______ array.', 'associative'),
(45, '______ function is used to sort an array in ascending order.', 'sort'),
(46, 'The PHP function used to stop script execution is ______.', 'exit'),
(47, '______ is used to handle runtime errors gracefully in PHP.', 'try-catch'),
(48, 'The PHP keyword used to define a function is ______.', 'function'),
(49, '______ is the scope where variables are accessible only inside a function.', 'local');

-- --------------------------------------------------------

--
-- Table structure for table `findtheerror`
--

CREATE TABLE `findtheerror` (
  `error_id` int NOT NULL,
  `buggy_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `answer_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `findtheerror`
--

INSERT INTO `findtheerror` (`error_id`, `buggy_code`, `answer_description`, `correct_code`) VALUES
(1, 'echo Hello World;', 'String must be enclosed in quotes.', 'echo \"Hello World\";'),
(2, '$a = 10\r\necho $a;', 'Missing semicolon after variable assignment.', '$a = 10;\r\necho $a;'),
(3, 'if($a = 5) { echo \"Yes\"; }', 'Assignment operator used instead of comparison operator.', 'if($a == 5) { echo \"Yes\"; }'),
(4, 'echo $name', 'Missing semicolon at the end of statement.', 'echo $name;'),
(5, '$1name = \"Ravi\";', 'Variable name cannot start with a number.', '$name1 = \"Ravi\";'),
(6, 'echo strtoupper[\"php\"];', 'Function call syntax is incorrect.', 'echo strtoupper(\"php\");'),
(7, 'for($i=0; $i<5; $i++) echo $i', 'Missing semicolon after echo statement.', 'for($i=0; $i<5; $i++) echo $i;'),
(8, 'while($i < 5) { echo $i; }', 'Loop variable is never incremented.', 'while($i < 5) { echo $i; $i++; }'),
(9, 'if($a > 10) echo \"High\" else echo \"Low\";', 'Missing semicolon before else.', 'if($a > 10) echo \"High\"; else echo \"Low\";'),
(10, 'echo strlen $str;', 'Function arguments must be inside parentheses.', 'echo strlen($str);'),
(11, '$arr = array(1,2,3\r\necho count($arr);', 'Missing closing parenthesis in array declaration.', '$arr = array(1,2,3);\r\necho count($arr);'),
(12, 'echo $_post[\"name\"];', 'PHP superglobals are case-sensitive.', 'echo $_POST[\"name\"];'),
(13, 'include \"header.php\"', 'Missing semicolon after include statement.', 'include \"header.php\";'),
(14, 'function add($a, $b) { return a+b; }', 'Variables must have $ symbol.', 'function add($a, $b) { return $a + $b; }'),
(15, '$conn = mysqli_connect(\"localhost\",\"root\",\"\"); mysqli_select_db(\"test\");', 'Database connection object not passed to function.', '$conn = mysqli_connect(\"localhost\",\"root\",\"\");\r\nmysqli_select_db($conn,\"test\");'),
(16, 'echo date;', 'date is a function and must be called.', 'echo date(\"Y-m-d\");'),
(17, 'if(isset $a)) { echo $a; }', 'Incorrect isset() syntax.', 'if(isset($a)) { echo $a; }'),
(18, 'switch($day) case \"Mon\": echo \"Monday\";', 'Missing curly braces in switch statement.', 'switch($day) {\r\ncase \"Mon\": echo \"Monday\"; break;\r\n}'),
(19, 'echo \"Sum is \" . $a + $b;', 'Operator precedence error.', 'echo \"Sum is \" . ($a + $b);'),
(20, '$a == 10;', 'Comparison operator used instead of assignment.', '$a = 10;'),
(21, 'echo strtoupper($str;', 'Missing closing parenthesis.', 'echo strtoupper($str);'),
(22, 'foreach($arr as $value) echo value;', 'Missing $ before variable name.', 'foreach($arr as $value) echo $value;'),
(23, 'session start();', 'Incorrect function name for session.', 'session_start();'),
(24, 'echo $_SERVER[\"PHP_SELF\";', 'Missing closing bracket.', 'echo $_SERVER[\"PHP_SELF\"];'),
(25, '$file = fopen(\"test.txt\",\"r\"); fread($file);', 'fread() requires length parameter.', 'fread($file, filesize(\"test.txt\"));'),
(26, 'header(\"location:index.php\");', 'Header redirection requires Location with capital L.', 'header(\"Location: index.php\");'),
(27, 'setcookie(\"user\",\"Ravi\"); echo $_COOKIE[\"user\"];', 'Cookie is available only after page reload.', 'setcookie(\"user\",\"Ravi\");'),
(28, 'if($a > 5 && < 10) echo \"Valid\";', 'Second condition missing variable.', 'if($a > 5 && $a < 10) echo \"Valid\";'),
(29, '$arr = [1,2,3]; echo $arr;', 'Array cannot be echoed directly.', 'print_r($arr);'),
(30, 'echo count;', 'count is a function and must be called.', 'echo count($arr);'),
(31, 'require \"config.php\"', 'Missing semicolon after require.', 'require \"config.php\";'),
(32, 'do { echo $i; } while($i < 5);', 'Loop variable not incremented.', 'do { echo $i; $i++; } while($i < 5);'),
(33, 'if($a === \"10\") echo true;', 'true should be string or boolean context.', 'if($a === \"10\") echo \"true\";'),
(34, 'echo strtolower[\"HELLO\"];', 'Incorrect function syntax.', 'echo strtolower(\"HELLO\");'),
(35, 'function test { echo \"Hi\"; }', 'Missing parentheses in function declaration.', 'function test() { echo \"Hi\"; }'),
(36, '$a = \"10\"; echo $a + \"5\";', 'Implicit type conversion not recommended.', '$a = 10; echo $a + 5;'),
(37, 'mysql_connect(\"localhost\",\"root\",\"\");', 'mysql extension is deprecated.', 'mysqli_connect(\"localhost\",\"root\",\"\");'),
(38, 'echo implode($arr,\",\");', 'Parameters order is incorrect.', 'echo implode(\",\",$arr);'),
(39, 'if(empty($a) == false) echo $a;', 'Unnecessary comparison with false.', 'if(!empty($a)) echo $a;'),
(40, 'for($i=0; $i<=5; $i--)', 'Wrong increment/decrement operator.', 'for($i=0; $i<=5; $i++)'),
(41, 'echo $_GET[name];', 'Array index must be in quotes.', 'echo $_GET[\"name\"];'),
(42, 'try { echo 10/0; }', 'Catch block missing.', 'try { echo 10/0; } catch(Exception $e) {}'),
(43, 'exit;', 'No error but exit should be conditional in practice.', 'if($error) exit;'),
(44, 'echo trim $str;', 'Function arguments must be in parentheses.', 'echo trim($str);'),
(45, 'password_hash($pass);', 'Algorithm not specified.', 'password_hash($pass, PASSWORD_DEFAULT);'),
(46, 'password_verify($pass, $hash, $extra);', 'password_verify accepts only two parameters.', 'password_verify($pass, $hash);'),
(47, 'echo json_encode;', 'json_encode is a function.', 'echo json_encode($data);');

-- --------------------------------------------------------

--
-- Table structure for table `fixthecode`
--

CREATE TABLE `fixthecode` (
  `fix_id` int NOT NULL,
  `problem_statement` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `buggy_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_code` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fixthecode`
--

INSERT INTO `fixthecode` (`fix_id`, `problem_statement`, `buggy_code`, `correct_code`) VALUES
(1, 'Display Hello World using PHP.', 'echo Hello World;', 'echo \"Hello World\";'),
(2, 'Assign value 10 to a variable and display it.', '$a == 10; echo $a;', '$a = 10; echo $a;'),
(3, 'Print a variable value.', 'echo $name', 'echo $name;'),
(4, 'Check if variable $a is equal to 5.', 'if($a = 5) { echo \"Yes\"; }', 'if($a == 5) { echo \"Yes\"; }'),
(5, 'Create a valid PHP variable.', '$1value = 100;', '$value1 = 100;'),
(6, 'Convert string to uppercase.', 'echo strtoupper[\"php\"];', 'echo strtoupper(\"php\");'),
(7, 'Print numbers from 1 to 5 using for loop.', 'for($i=1;$i<=5;$i--) echo $i;', 'for($i=1;$i<=5;$i++) echo $i;'),
(8, 'Fix infinite while loop.', 'while($i < 5) { echo $i; }', 'while($i < 5) { echo $i; $i++; }'),
(9, 'Use if-else correctly.', 'if($a > 10) echo \"High\" else echo \"Low\";', 'if($a > 10) echo \"High\"; else echo \"Low\";'),
(10, 'Find length of a string.', 'echo strlen $str;', 'echo strlen($str);'),
(11, 'Create an array and count elements.', '$arr = array(1,2,3 echo count($arr);', '$arr = array(1,2,3); echo count($arr);'),
(12, 'Access POST form data correctly.', 'echo $_post[\"name\"];', 'echo $_POST[\"name\"];'),
(13, 'Include a PHP file.', 'include \"config.php\"', 'include \"config.php\";'),
(14, 'Create a function to add two numbers.', 'function add($a,$b){ return a+b; }', 'function add($a,$b){ return $a + $b; }'),
(15, 'Select database after MySQL connection.', 'mysqli_select_db(\"test\");', 'mysqli_select_db($conn,\"test\");'),
(16, 'Print current date.', 'echo date;', 'echo date(\"Y-m-d\");'),
(17, 'Check if variable is set.', 'if(isset $a)) echo $a;', 'if(isset($a)) echo $a;'),
(18, 'Use switch statement properly.', 'switch($day) case \"Mon\": echo \"Monday\";', 'switch($day){ case \"Mon\": echo \"Monday\"; break; }'),
(19, 'Fix string and number addition.', 'echo \"Sum = \" . $a + $b;', 'echo \"Sum = \" . ($a + $b);'),
(20, 'Assign value properly.', '$a == 20;', '$a = 20;'),
(21, 'Use foreach loop correctly.', 'foreach($arr as $val) echo val;', 'foreach($arr as $val) echo $val;'),
(22, 'Start session properly.', 'session start();', 'session_start();'),
(23, 'Access PHP_SELF server variable.', 'echo $_SERVER[\"PHP_SELF\";', 'echo $_SERVER[\"PHP_SELF\"];'),
(24, 'Read file content correctly.', '$file = fopen(\"a.txt\",\"r\"); fread($file);', '$file = fopen(\"a.txt\",\"r\"); fread($file, filesize(\"a.txt\"));'),
(25, 'Redirect user to index page.', 'header(\"location:index.php\");', 'header(\"Location: index.php\");'),
(26, 'Fix logical AND condition.', 'if($a > 5 && < 10) echo \"Valid\";', 'if($a > 5 && $a < 10) echo \"Valid\";'),
(27, 'Print array properly.', 'echo $arr;', 'print_r($arr);'),
(28, 'Use count function properly.', 'echo count;', 'echo count($arr);'),
(29, 'Use require statement.', 'require \"header.php\"', 'require \"header.php\";'),
(30, 'Fix do-while loop.', 'do { echo $i; } while($i < 5);', 'do { echo $i; $i++; } while($i < 5);'),
(31, 'Use strtolower function.', 'echo strtolower[\"HELLO\"];', 'echo strtolower(\"HELLO\");'),
(32, 'Create function correctly.', 'function show { echo \"Hi\"; }', 'function show(){ echo \"Hi\"; }'),
(33, 'Fix numeric addition.', '$a=\"10\"; echo $a+\"5\";', '$a=10; echo $a+5;'),
(34, 'Use mysqli instead of deprecated mysql.', 'mysql_connect(\"localhost\",\"root\",\"\");', 'mysqli_connect(\"localhost\",\"root\",\"\");'),
(35, 'Fix implode syntax.', 'echo implode($arr,\",\");', 'echo implode(\",\",$arr);'),
(36, 'Check empty variable properly.', 'if(empty($a)==false) echo $a;', 'if(!empty($a)) echo $a;'),
(37, 'Fix for loop increment.', 'for($i=0;$i<=5;$i--)', 'for($i=0;$i<=5;$i++)'),
(38, 'Access GET parameter correctly.', 'echo $_GET[name];', 'echo $_GET[\"name\"];'),
(39, 'Add catch block in try-catch.', 'try { echo 10/0; }', 'try { echo 10/0; } catch(Exception $e) {}'),
(40, 'Trim string properly.', 'echo trim $str;', 'echo trim($str);'),
(41, 'Hash password securely.', 'password_hash($pass);', 'password_hash($pass, PASSWORD_DEFAULT);'),
(42, 'Verify password correctly.', 'password_verify($pass,$hash,$extra);', 'password_verify($pass,$hash);'),
(43, 'Encode data into JSON.', 'echo json_encode;', 'echo json_encode($data);');

-- --------------------------------------------------------

--
-- Table structure for table `flashcardflipper`
--

CREATE TABLE `flashcardflipper` (
  `card_id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flashcardflipper`
--

INSERT INTO `flashcardflipper` (`card_id`, `question`, `correct_answer`) VALUES
(1, 'What is PHP?', 'PHP is a server-side scripting language used to create dynamic and interactive web applications.'),
(2, 'What does PHP stand for?', 'PHP stands for Hypertext Preprocessor.'),
(3, 'Is PHP a client-side or server-side language?', 'PHP is a server-side scripting language.'),
(4, 'What is the default file extension of PHP?', 'The default file extension of PHP is .php.'),
(5, 'How do you output text in PHP?', 'Text can be output using echo or print statements.'),
(6, 'What symbol is used before PHP variables?', 'All PHP variables start with the $ symbol.'),
(7, 'How do you write a single-line comment in PHP?', 'Single-line comments are written using // or #.'),
(8, 'How do you write a multi-line comment in PHP?', 'Multi-line comments are written using /* and */.'),
(9, 'What is a variable in PHP?', 'A variable is used to store data that can be reused in a script.'),
(10, 'What is an array in PHP?', 'An array is a data structure that stores multiple values in a single variable.'),
(11, 'What are the types of arrays in PHP?', 'Indexed arrays, associative arrays, and multidimensional arrays.'),
(12, 'What is an associative array?', 'An array that uses named keys instead of numeric indexes.'),
(13, 'Which function is used to find the length of a string?', 'The strlen() function is used to find string length.'),
(14, 'Which function converts a string to uppercase?', 'The strtoupper() function converts a string to uppercase.'),
(15, 'Which function converts a string to lowercase?', 'The strtolower() function converts a string to lowercase.'),
(16, 'How do you concatenate strings in PHP?', 'Strings are concatenated using the dot (.) operator.'),
(17, 'What is a conditional statement?', 'A conditional statement is used to perform actions based on conditions.'),
(18, 'Which conditional statements are available in PHP?', 'if, if-else, if-elseif-else, and switch.'),
(19, 'What is a loop?', 'A loop is used to execute a block of code repeatedly.'),
(20, 'What are the types of loops in PHP?', 'for, while, do-while, and foreach.'),
(21, 'Which loop executes at least once?', 'The do-while loop executes at least once.'),
(22, 'What is a function in PHP?', 'A function is a reusable block of code that performs a specific task.'),
(23, 'How do you define a function in PHP?', 'Functions are defined using the function keyword.'),
(24, 'What is a session?', 'A session is used to store user data across multiple pages.'),
(25, 'Which function starts a session?', 'session_start() starts a session.'),
(26, 'How do you destroy a session?', 'A session can be destroyed using session_destroy().'),
(27, 'What is a cookie?', 'A cookie is a small piece of data stored on the user’s browser.'),
(28, 'Which function is used to set a cookie?', 'setcookie() is used to set a cookie.'),
(29, 'What is $_GET?', '$_GET is a superglobal used to collect form data sent via URL.'),
(30, 'What is $_POST?', '$_POST is a superglobal used to collect form data sent via POST method.'),
(31, 'What is $_SESSION?', '$_SESSION is a superglobal used to store session variables.'),
(32, 'What is $_SERVER?', '$_SERVER is a superglobal that holds server and execution environment information.'),
(33, 'What is MySQLi?', 'MySQLi is a PHP extension used to connect to MySQL databases.'),
(34, 'What does SQL stand for?', 'SQL stands for Structured Query Language.'),
(35, 'Which SQL command retrieves data?', 'SELECT command retrieves data from a database.'),
(36, 'What is a primary key?', 'A primary key uniquely identifies each record in a database table.'),
(37, 'What is SQL Injection?', 'SQL Injection is a security attack that manipulates SQL queries.'),
(38, 'How can SQL Injection be prevented?', 'By using prepared statements and parameter binding.'),
(39, 'Which function is used to hash passwords?', 'password_hash() is used to securely hash passwords.'),
(40, 'Which function verifies hashed passwords?', 'password_verify() verifies a hashed password.'),
(41, 'What is include in PHP?', 'include is used to insert the content of one PHP file into another.'),
(42, 'What is require in PHP?', 'require includes a file and stops execution if the file is missing.'),
(43, 'What is the difference between include and require?', 'include shows a warning on failure, while require causes a fatal error.'),
(44, 'What is JSON?', 'JSON is a lightweight data-interchange format.'),
(45, 'Which function converts data to JSON?', 'json_encode() converts PHP data into JSON format.'),
(46, 'Which function converts JSON to PHP array?', 'json_decode() converts JSON data to PHP array.'),
(47, 'What is error reporting in PHP?', 'Error reporting is used to display or log PHP runtime errors.'),
(48, 'How do you stop script execution in PHP?', 'exit() or die() stops script execution.');

-- --------------------------------------------------------

--
-- Table structure for table `predictoutput`
--

CREATE TABLE `predictoutput` (
  `predict_id` int NOT NULL,
  `code_snippet` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `expected_output` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `predictoutput`
--

INSERT INTO `predictoutput` (`predict_id`, `code_snippet`, `expected_output`, `description`) VALUES
(1, 'echo \"Hello PHP\";', 'Hello PHP', 'Simple echo statement outputs the string.'),
(2, '$a = 5; $b = 10; echo $a + $b;', '15', 'Adds two integers and prints the result.'),
(3, 'echo 10 + \"20\";', '30', 'PHP performs implicit type conversion.'),
(4, '$x = \"PHP\"; echo $x;', 'PHP', 'Displays the value of the variable.'),
(5, 'echo strlen(\"Hello\");', '5', 'strlen() returns number of characters.'),
(6, '$a = 5; if($a > 3) echo \"Yes\"; else echo \"No\";', 'Yes', 'Condition is true, so Yes is printed.'),
(7, 'for($i=1; $i<=3; $i++) echo $i;', '123', 'Loop runs three times and prints numbers.'),
(8, '$i = 1; while($i <= 3) { echo $i; $i++; }', '123', 'While loop prints values of i.'),
(9, '$i = 1; do { echo $i; $i++; } while($i <= 3);', '123', 'Do-while loop executes at least once.'),
(10, '$arr = array(10,20,30); echo $arr[1];', '20', 'Accessing second element of array.'),
(11, '$arr = [\"a\"=>1,\"b\"=>2]; echo $arr[\"b\"];', '2', 'Associative array access.'),
(12, 'echo strtoupper(\"php\");', 'PHP', 'Converts string to uppercase.'),
(13, 'echo strtolower(\"HELLO\");', 'hello', 'Converts string to lowercase.'),
(14, 'echo \"5\" . \"10\";', '510', 'Dot operator concatenates strings.'),
(15, 'echo 5 . 10;', '510', 'Numbers are treated as strings during concatenation.'),
(16, '$a = 10; $a += 5; echo $a;', '15', 'Compound assignment operator.'),
(17, '$a = 10; echo ++$a;', '11', 'Pre-increment increments before printing.'),
(18, '$a = 10; echo $a++;', '10', 'Post-increment prints value before incrementing.'),
(19, 'echo ($a = 5) + 2;', '7', 'Assignment inside expression.'),
(20, '$arr = [1,2,3]; echo count($arr);', '3', 'count() returns number of elements.'),
(21, 'foreach([1,2,3] as $v) echo $v;', '123', 'foreach iterates over array values.'),
(22, 'echo isset($x);', '', 'Variable is not set, so nothing is printed.'),
(23, '$x = \"\"; echo empty($x);', '1', 'empty() returns true for empty string.'),
(24, 'echo gettype(10);', 'integer', 'gettype() returns data type.'),
(25, 'function test(){ return \"Hi\"; } echo test();', 'Hi', 'Function returns string.'),
(26, 'echo date(\"Y\");', 'Current year', 'date() outputs current year.'),
(27, '$a = 5; $b = \"5\"; echo ($a == $b);', '1', '== checks value only, not type.'),
(28, '$a = 5; $b = \"5\"; echo ($a === $b);', '', '=== checks value and type, condition false.'),
(29, 'echo trim(\" PHP \");', 'PHP', 'trim() removes whitespace.'),
(30, 'echo substr(\"PHP\",1);', 'HP', 'substr() extracts substring.'),
(31, 'echo strpos(\"Hello\",\"e\");', '1', 'strpos() returns position of character.'),
(32, '$arr = [\"A\",\"B\",\"C\"]; array_pop($arr); echo count($arr);', '2', 'array_pop() removes last element.'),
(33, '$arr = [\"A\",\"B\"]; array_push($arr,\"C\"); echo count($arr);', '3', 'array_push() adds element.'),
(34, 'echo implode(\"-\",[\"A\",\"B\",\"C\"]);', 'A-B-C', 'implode() joins array elements.'),
(35, 'echo explode(\",\", \"A,B,C\")[1];', 'B', 'explode() splits string into array.'),
(36, '$x = null; echo is_null($x);', '1', 'is_null() checks null value.'),
(37, 'echo json_encode([\"a\"=>1]);', '{\"a\":1}', 'json_encode converts array to JSON.'),
(38, '$a = 10; echo ($a > 5 && $a < 15);', '1', 'Logical AND condition is true.'),
(39, '$a = 10; echo ($a < 5 || $a > 8);', '1', 'Logical OR condition.'),
(40, 'echo floor(5.9);', '5', 'floor() rounds down.'),
(41, 'echo ceil(5.1);', '6', 'ceil() rounds up.'),
(42, 'echo round(5.5);', '6', 'round() rounds to nearest integer.'),
(43, 'echo abs(-10);', '10', 'abs() returns absolute value.'),
(44, 'echo max(1,5,3);', '5', 'max() returns highest value.'),
(45, 'echo min(1,5,3);', '1', 'min() returns lowest value.'),
(46, 'echo pow(2,3);', '8', 'pow() calculates power.'),
(47, 'echo strlen(\"\");', '0', 'Empty string length is zero.'),
(48, 'echo (int)\"100\";', '100', 'Type casting string to integer.');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `quiz_id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `option_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `option_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `option_3` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `option_4` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz`
--

INSERT INTO `quiz` (`quiz_id`, `question`, `option_1`, `option_2`, `option_3`, `option_4`, `correct_answer`) VALUES
(1, 'What does PHP stand for?', 'Personal Home Page', 'PHP: Hypertext Preprocessor', 'Private HTML Pages', 'Public Hypertext Protocol', 'PHP: Hypertext Preprocessor'),
(2, 'Which symbol is used to start a variable in PHP?', '@', '#', '$', '&', '$'),
(3, 'What is the correct way to end a PHP statement?', '.', ';', ':', ',', ';'),
(4, 'Which tag is used to start PHP code?', '<?php', '<php>', '<?>', '<script>', '<?php'),
(5, 'What is the correct way to create a comment in PHP?', '/* comment */', '// comment', '# comment', 'All of the above', 'All of the above'),
(6, 'Which function is used to output text in PHP?', 'print()', 'echo', 'write()', 'Both A and B', 'Both A and B'),
(7, 'What is the file extension for PHP files?', '.html', '.php', '.phps', '.xml', '.php'),
(8, 'PHP is a ____ language?', 'Client-side', 'Server-side', 'Both', 'None', 'Server-side'),
(9, 'Which version of PHP introduced namespaces?', 'PHP 4', 'PHP 5.3', 'PHP 7', 'PHP 8', 'PHP 5.3'),
(10, 'What is the latest stable version of PHP as of 2024?', 'PHP 7.4', 'PHP 8.0', 'PHP 8.2', 'PHP 8.3', 'PHP 8.3'),
(11, 'Which of these is NOT a valid variable name?', '$myVar', '$_myVar', '$123var', '$my_var', '$123var'),
(12, 'What is the correct way to create a string variable?', '$str = Hello;', '$str = \"Hello\";', 'str = \"Hello\";', '$str == \"Hello\";', '$str = \"Hello\";'),
(13, 'Which function checks if a variable is set?', 'is_set()', 'isset()', 'check_var()', 'var_isset()', 'isset()'),
(14, 'What does the gettype() function return?', 'Variable value', 'Variable type', 'Variable name', 'Variable size', 'Variable type'),
(15, 'Which is NOT a valid data type in PHP?', 'Integer', 'Float', 'Character', 'Boolean', 'Character'),
(16, 'What is the output of: echo 5 + \"5\";', '10', '55', 'Error', '5', '10'),
(17, 'Which function converts a string to an integer?', 'intval()', 'int()', 'to_int()', 'convert_int()', 'intval()'),
(18, 'What is NULL in PHP?', 'Empty string', 'Zero', 'Special type with no value', 'False', 'Special type with no value'),
(19, 'Which operator checks if two values are identical?', '==', '===', '!=', '!==', '==='),
(20, 'What is the result of: var_dump(0 == \"0\");', 'bool(true)', 'bool(false)', 'int(0)', 'string(0)', 'bool(true)'),
(21, 'How do you create an array in PHP?', 'array()', '[]', '$arr = array()', 'All of the above', 'All of the above'),
(22, 'Which function returns the number of elements in an array?', 'length()', 'size()', 'count()', 'sizeof()', 'count()'),
(23, 'How do you add an element to the end of an array?', 'array_add()', 'array_push()', 'push()', 'add_element()', 'array_push()'),
(24, 'Which function removes the last element from an array?', 'array_pop()', 'array_remove()', 'pop()', 'remove_last()', 'array_pop()'),
(25, 'What is an associative array?', 'Indexed array', 'Array with named keys', 'Multi-dimensional array', 'Sorted array', 'Array with named keys'),
(26, 'Which function sorts an array in ascending order?', 'sort()', 'asort()', 'ksort()', 'rsort()', 'sort()'),
(27, 'How do you check if a key exists in an array?', 'key_exists()', 'array_key_exists()', 'isset()', 'Both B and C', 'Both B and C'),
(28, 'Which function merges two or more arrays?', 'array_combine()', 'array_merge()', 'merge_array()', 'combine()', 'array_merge()'),
(29, 'What does array_slice() do?', 'Removes elements', 'Extracts a portion', 'Splits array', 'Sorts array', 'Extracts a portion'),
(30, 'Which function searches for a value in an array?', 'array_find()', 'array_search()', 'find()', 'search()', 'array_search()'),
(31, 'What is the correct way to create a function?', 'function myFunc()', 'create myFunc()', 'def myFunc()', 'func myFunc()', 'function myFunc()'),
(32, 'How do you call a function named \"myFunction\"?', 'call myFunction()', 'myFunction()', 'execute myFunction()', 'run myFunction()', 'myFunction()'),
(33, 'Which keyword is used to return a value from a function?', 'give', 'return', 'send', 'output', 'return'),
(34, 'What are default parameters in functions?', 'Required parameters', 'Optional parameters with default values', 'Variable parameters', 'Reference parameters', 'Optional parameters with default values'),
(35, 'Which function checks if a function exists?', 'function_exists()', 'is_function()', 'func_exists()', 'check_function()', 'function_exists()'),
(36, 'Which is the correct syntax for an if statement?', 'if $x > 5', 'if ($x > 5)', 'if x > 5 then', 'if {$x > 5}', 'if ($x > 5)'),
(37, 'Which loop is used when you know the number of iterations?', 'while', 'do-while', 'for', 'foreach', 'for'),
(38, 'What does the break statement do?', 'Stops script', 'Exits loop', 'Skips iteration', 'Returns value', 'Exits loop'),
(39, 'Which keyword skips the current iteration?', 'skip', 'next', 'continue', 'pass', 'continue'),
(40, 'What is the correct syntax for a switch statement?', 'switch ($var) case', 'switch ($var) { case: }', 'switch ($var) { case value: }', 'switch $var case', 'switch ($var) { case value: }'),
(41, 'Which superglobal is used to collect form data with POST?', '$_GET', '$_POST', '$_REQUEST', '$_FORM', '$_POST'),
(42, 'Which superglobal contains session variables?', '$_SESSION', '$_COOKIE', '$_SERVER', '$_ENV', '$_SESSION'),
(43, 'How do you start a session in PHP?', 'start_session()', 'session_start()', 'begin_session()', 'init_session()', 'session_start()'),
(44, 'Which superglobal contains server information?', '$_SERVER', '$_ENV', '$_SYSTEM', '$_INFO', '$_SERVER'),
(45, 'How do you retrieve a cookie value?', '$_COOKIE[\"name\"]', 'get_cookie(\"name\")', 'cookie(\"name\")', '$COOKIE[\"name\"]', '$_COOKIE[\"name\"]'),
(46, 'What is the correct way to include a file?', 'include \"file.php\"', 'require \"file.php\"', 'include_once \"file.php\"', 'All of the above', 'All of the above'),
(47, 'Which function connects to a MySQL database?', 'mysql_connect()', 'mysqli_connect()', 'db_connect()', 'connect_db()', 'mysqli_connect()'),
(48, 'What does the die() function do?', 'Stops and outputs message', 'Deletes variable', 'Ends loop', 'Closes file', 'Stops and outputs message'),
(49, 'Which operator is used for string concatenation?', '+', '.', '&', ',', '.'),
(50, 'What is the difference between include and require?', 'No difference', 'require stops on error, include continues', 'include stops on error, require continues', 'require is faster', 'require stops on error, include continues');

-- --------------------------------------------------------

--
-- Table structure for table `treasurehunt`
--

CREATE TABLE `treasurehunt` (
  `hunt_id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `correct_answer` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treasurehunt`
--

INSERT INTO `treasurehunt` (`hunt_id`, `question`, `correct_answer`, `description`) VALUES
(1, 'I am a server-side language used to make dynamic websites. Who am I?', 'PHP', 'Basic identification of PHP language.'),
(2, 'I start with a dollar sign and store values. What am I?', 'variable', 'Refers to PHP variables.'),
(3, 'I am used to display output instantly on the screen. Find me.', 'echo', 'Output statement in PHP.'),
(4, 'I decide which path the code should take. Who am I?', 'if', 'Conditional statement clue.'),
(5, 'I repeat code until a condition becomes false. Name me.', 'loop', 'Generic loop concept.'),
(6, 'I always execute at least once before checking condition. Who am I?', 'do-while', 'Special loop behavior.'),
(7, 'I store multiple values inside one variable. What am I?', 'array', 'Array concept.'),
(8, 'I use key-value pairs instead of index numbers. Identify me.', 'associative array', 'Associative array clue.'),
(9, 'I help reuse code again and again. What am I?', 'function', 'Function purpose.'),
(10, 'I connect PHP with MySQL databases securely. Guess me.', 'mysqli', 'Database connection extension.'),
(11, 'I uniquely identify each row in a database table. Who am I?', 'primary key', 'Database concept.'),
(12, 'I protect data across multiple pages. Find me.', 'session', 'Session usage.'),
(13, 'I start a session before storing user data. Name me.', 'session_start()', 'Session initialization function.'),
(14, 'I store small data on the user browser. What am I?', 'cookie', 'Cookie concept.'),
(15, 'I collect form data sent via URL. Who am I?', '$_GET', 'GET superglobal.'),
(16, 'I collect form data sent securely. Identify me.', '$_POST', 'POST superglobal.'),
(17, 'I hold server and script information. Guess me.', '$_SERVER', 'Server superglobal.'),
(18, 'I remove unwanted spaces from strings. Who am I?', 'trim()', 'String cleaning function.'),
(19, 'I convert text to uppercase. Find me.', 'strtoupper()', 'String transformation.'),
(20, 'I count how many elements are inside an array. Name me.', 'count()', 'Array counting function.'),
(21, 'I stop script execution immediately. What am I?', 'exit()', 'Script termination.'),
(22, 'I redirect users to another page. Identify me.', 'header()', 'HTTP redirection.'),
(23, 'I include a file and stop execution if missing. Who am I?', 'require', 'File inclusion behavior.'),
(24, 'I include a file but continue execution on error. Guess me.', 'include', 'Include vs require difference.'),
(25, 'I secure passwords before storing them. Find me.', 'password_hash()', 'Password security.'),
(26, 'I verify hashed passwords. Name me.', 'password_verify()', 'Password checking.'),
(27, 'I prevent SQL Injection when used with queries. Who am I?', 'prepared statements', 'Database security concept.'),
(28, 'I represent true or false values. Identify me.', 'boolean', 'Boolean data type.'),
(29, 'I join strings together in PHP. What symbol am I?', '.', 'String concatenation operator.'),
(30, 'I check both value and data type. Guess me.', '===', 'Strict comparison operator.'),
(31, 'I convert PHP data into JSON format. Who am I?', 'json_encode()', 'JSON encoding.'),
(32, 'I convert JSON back into PHP data. Name me.', 'json_decode()', 'JSON decoding.'),
(33, 'I show how many characters a string has. Find me.', 'strlen()', 'String length function.'),
(34, 'I remove the last element from an array. Who am I?', 'array_pop()', 'Array manipulation.'),
(35, 'I add an element to the end of an array. Identify me.', 'array_push()', 'Array insertion.'),
(36, 'I break out of a loop immediately. What am I?', 'break', 'Loop control statement.'),
(37, 'I skip the current iteration and continue looping. Guess me.', 'continue', 'Loop skipping.'),
(38, 'I handle runtime errors gracefully. Who am I?', 'try-catch', 'Exception handling.'),
(39, 'I tell PHP to start interpreting code. Find me.', '<?php', 'PHP opening tag.'),
(40, 'I end PHP code execution block. Name me.', '?>', 'PHP closing tag.'),
(41, 'I define constants whose values cannot change. Who am I?', 'define()', 'Constant creation.'),
(42, 'I represent nothing or no value. Guess me.', 'null', 'Null data type.'),
(43, 'I check whether a variable exists. Find me.', 'isset()', 'Variable existence check.'),
(44, 'I check whether a variable is empty. Identify me.', 'empty()', 'Empty check.'),
(45, 'I tell PHP to report runtime errors. Who am I?', 'error_reporting()', 'Error handling function.'),
(46, 'I fetch data from a database table. Guess my SQL name.', 'SELECT', 'SQL data retrieval.'),
(47, 'I ensure a loop runs a fixed number of times. Who am I?', 'for', 'For loop usage.'),
(48, 'I convert a string to lowercase. Find me.', 'strtolower()', 'Lowercase conversion.'),
(49, 'I round numbers to nearest integer. Who am I?', 'round()', 'Math rounding function.');

-- --------------------------------------------------------

--
-- Table structure for table `truefalse`
--

CREATE TABLE `truefalse` (
  `tf_id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_true` tinyint(1) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `truefalse`
--

INSERT INTO `truefalse` (`tf_id`, `question`, `is_true`, `description`) VALUES
(1, 'PHP stands for Hypertext Preprocessor.', 1, 'Correct! PHP is a recursive acronym: PHP: Hypertext Preprocessor.'),
(2, 'HTML stands for Hypertext Markup Language.', 1, 'Correct. HTML is used to structure web pages.'),
(3, 'CSS is a programming language.', 0, 'Incorrect. CSS is a styling language.'),
(4, 'JavaScript can run on both client-side and server-side.', 1, 'Correct! With Node.js, JavaScript runs server-side too.'),
(5, 'The <head> tag in HTML is used to display content on the webpage.', 0, 'Incorrect. The <head> tag contains metadata and links.'),
(6, 'PHP scripts are executed on the server.', 1, 'Correct. PHP is server-side scripting.'),
(7, 'The === operator in PHP checks both value and type.', 1, 'Correct. === is strict equality in PHP.'),
(8, 'In PHP, variables must start with a $ symbol.', 1, 'Correct. All PHP variables start with $.'),
(9, 'MySQL is a programming language.', 0, 'Incorrect. MySQL is a relational database management system.'),
(10, 'CSS stands for Cascading Style Sheets.', 1, 'Correct. CSS is used for styling HTML content.'),
(11, 'PHP can be embedded in HTML.', 1, 'Correct. PHP code can be placed inside HTML using <?php ?> tags.'),
(12, 'The <body> tag in HTML contains the visible content of the page.', 1, 'Correct. <body> is for visible content.'),
(13, 'JavaScript is case-insensitive.', 0, 'Incorrect. JavaScript is case-sensitive.'),
(14, 'HTML comments are written as <!-- comment -->.', 1, 'Correct. That is the proper syntax for HTML comments.'),
(15, 'PHP files must have a .php extension to execute properly.', 1, 'Correct. Web servers recognize .php files to process PHP code.'),
(16, 'SQL stands for Structured Query Language.', 1, 'Correct. SQL is used to interact with databases.'),
(17, 'The <img> tag is self-closing in HTML.', 1, 'Correct. <img> does not require a closing tag.'),
(18, 'In PHP, echo and print are functions that output data.', 1, 'Correct. echo and print display output.'),
(19, 'Java and JavaScript are the same language.', 0, 'Incorrect. Java and JavaScript are different languages.'),
(20, 'PHP supports object-oriented programming.', 1, 'Correct. PHP supports classes, objects, inheritance, and more.'),
(21, 'The SELECT statement is used to delete data from a database.', 0, 'Incorrect. DELETE is used to remove data.'),
(22, 'In CSS, # is used to select an element by ID.', 1, 'Correct. #id selects by ID.'),
(23, 'In CSS, . is used to select elements by class.', 1, 'Correct. .class selects by class name.'),
(24, 'PHP variables are case-insensitive.', 0, 'Incorrect. Variables in PHP are case-sensitive.'),
(25, 'The POST method sends data in the URL.', 0, 'Incorrect. POST sends data in the request body.'),
(26, 'The GET method appends data to the URL.', 1, 'Correct. GET sends data via query string.'),
(27, 'PHP arrays can hold multiple data types.', 1, 'Correct. Arrays in PHP can hold mixed types.'),
(28, 'HTML tables are created using the <table> tag.', 1, 'Correct. <table> is used for tables.'),
(29, 'In PHP, comments are written as // or /* */.', 1, 'Correct. Both single-line and multi-line comments are supported.'),
(30, 'A <div> is a block-level element in HTML.', 1, 'Correct. <div> creates block-level containers.'),
(31, 'A <span> is an inline element in HTML.', 1, 'Correct. <span> is inline.'),
(32, 'PHP supports constants using the define() function.', 1, 'Correct. Constants are defined using define().'),
(33, 'MySQL tables are case-sensitive on all platforms.', 0, 'Incorrect. Case sensitivity depends on OS and collation.'),
(34, 'PHP code can be written outside <?php ?> tags and still execute.', 0, 'Incorrect. PHP must be inside <?php ?>.'),
(35, 'The <form> tag is used to create forms in HTML.', 1, 'Correct. <form> is the container for input elements.'),
(36, 'HTML forms can only use the GET method.', 0, 'Incorrect. Forms can use GET or POST.'),
(37, 'PHP has built-in functions for string manipulation.', 1, 'Correct. Functions like strlen(), strpos(), str_replace() exist.'),
(38, 'The <a> tag is used to create hyperlinks.', 1, 'Correct. <a href=\"URL\">Link</a> creates a hyperlink.'),
(39, 'HTML5 introduced the <canvas> element.', 1, 'Correct. <canvas> is for graphics and drawings.'),
(40, 'PHP cannot connect to a MySQL database.', 0, 'Incorrect. PHP can connect using mysqli or PDO.'),
(41, 'The LIMIT clause in SQL restricts the number of returned rows.', 1, 'Correct. LIMIT sets row count.'),
(42, 'In JavaScript, == checks both type and value.', 0, 'Incorrect. == checks value only; === checks type and value.'),
(43, 'PHP 7 removed support for the old mysql_* functions.', 1, 'Correct. Use mysqli or PDO instead.'),
(44, 'The <ul> tag creates an unordered list in HTML.', 1, 'Correct. <ul> is unordered list.'),
(45, 'The <ol> tag creates an ordered list in HTML.', 1, 'Correct. <ol> is ordered list.'),
(46, 'JavaScript functions can be anonymous.', 1, 'Correct. Functions can be declared without a name.'),
(47, 'CSS can be used to change the font style.', 1, 'Correct. Font-family, font-size, and font-style are controlled via CSS.'),
(48, 'The alt attribute in <img> is mandatory in HTML5.', 0, 'Incorrect. It is recommended but not strictly mandatory.'),
(49, 'PHP variables can start with numbers.', 0, 'Incorrect. Variable names cannot start with numbers.'),
(50, 'SQL WHERE clause filters rows.', 1, 'Correct. WHERE specifies conditions.'),
(51, 'JavaScript arrays are zero-indexed.', 1, 'Correct. Indexing starts at 0.'),
(52, 'PHP supports associative arrays.', 1, 'Correct. Arrays can have key-value pairs.'),
(53, 'The <meta> tag is used for page metadata in HTML.', 1, 'Correct. Meta tags define charset, description, keywords.'),
(54, 'CSS margin controls the spacing outside an element.', 1, 'Correct. Margin is outer spacing.'),
(55, 'CSS padding controls the spacing inside an element.', 1, 'Correct. Padding is inner spacing.');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stream` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `institute_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `country` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `stream`, `institute_name`, `state`, `country`) VALUES
(2, 'Ravi Sav', 'ravi@gmail.com', '$2y$10$T46zPx0p6jFSL0mn2kpileJF6C.FkjUtoTiFsddzar3GDj/9N4Z4e', 'BCA', 'Darshan Univesity', 'Gujarat', 'India'),
(9, 'Vipul Bambhaniya', 'vipul@gmail.com', '$2y$10$ryph8Wxf82hf6Zag8Pw1xOM9UYHPDNczZciiX04dyp.PzsDnlK2yq', 'Commerce', 'Marwadi University', 'Gujarati', 'India'),
(11, 'Jenish Gohel', 'jenishgohel@gmail.com', '$2y$10$4TSvjyGI.rpaJXiC0F/D.O2rzq78BHgMoRoQWIcYAWJ5yIvhU9YjO', 'Commerce', 'Marwadi University', 'Gujarat', 'India'),
(12, 'Harsh Dodiya', 'harsh@gmail.com', '$2y$10$6Q8wTrpScZz10kGo/Ydd8ubuxVMYvc5jdRoZO2M3uqsuEWnLi2FqS', 'Science', 'Jk College', 'Gujarat', 'India'),
(14, 'Vipul Bambhaniya', 'vipul123@gmail.com', '$2y$10$JmEFZ7Yovlp381/M5gbn7emxm9vui8uER7HvIhKycxn7DLkijwIp6', 'Commerce', 'Marwadi University', 'Gujarat', 'INDIA'),
(15, 'Het Parmar', 'het@gmail.com', '$2y$10$1JYx134Srr9FHM4WqR5NkuOSOwwtS3GrY/wBYG368RvQKDap.YFq6', 'Arts', 'Marwadi University', 'Gujarat', 'INDIA');

-- --------------------------------------------------------

--
-- Table structure for table `user_game_progress`
--

CREATE TABLE `user_game_progress` (
  `progress_id` int NOT NULL,
  `user_id` int NOT NULL,
  `game_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'fill-blanks, true-false, quiz, etc.',
  `points_earned` int DEFAULT '0',
  `questions_attempted` int DEFAULT '0',
  `correct_answers` int DEFAULT '0',
  `wrong_answers` int DEFAULT '0',
  `accuracy` decimal(5,2) DEFAULT '0.00' COMMENT 'Percentage accuracy',
  `time_spent` int DEFAULT '0' COMMENT 'Time in seconds',
  `completed_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_game_progress`
--

INSERT INTO `user_game_progress` (`progress_id`, `user_id`, `game_type`, `points_earned`, `questions_attempted`, `correct_answers`, `wrong_answers`, `accuracy`, `time_spent`, `completed_at`) VALUES
(9, 9, 'true-false', 80, 10, 8, 2, 80.00, 0, '2026-01-09 01:13:43'),
(10, 9, 'mcq-quiz', 30, 10, 3, 7, 30.00, 0, '2026-01-09 01:19:56'),
(11, 9, 'fill-blanks', 20, 10, 2, 8, 20.00, 0, '2026-01-09 01:22:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_streaks`
--

CREATE TABLE `user_streaks` (
  `streak_id` int NOT NULL,
  `user_id` int NOT NULL,
  `current_streak` int DEFAULT '0',
  `longest_streak` int DEFAULT '0',
  `last_activity_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_streaks`
--

INSERT INTO `user_streaks` (`streak_id`, `user_id`, `current_streak`, `longest_streak`, `last_activity_date`) VALUES
(5, 9, 1, 1, '2026-01-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  ADD PRIMARY KEY (`fib_id`);

--
-- Indexes for table `findtheerror`
--
ALTER TABLE `findtheerror`
  ADD PRIMARY KEY (`error_id`);

--
-- Indexes for table `fixthecode`
--
ALTER TABLE `fixthecode`
  ADD PRIMARY KEY (`fix_id`);

--
-- Indexes for table `flashcardflipper`
--
ALTER TABLE `flashcardflipper`
  ADD PRIMARY KEY (`card_id`);

--
-- Indexes for table `predictoutput`
--
ALTER TABLE `predictoutput`
  ADD PRIMARY KEY (`predict_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`quiz_id`);

--
-- Indexes for table `treasurehunt`
--
ALTER TABLE `treasurehunt`
  ADD PRIMARY KEY (`hunt_id`);

--
-- Indexes for table `truefalse`
--
ALTER TABLE `truefalse`
  ADD PRIMARY KEY (`tf_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_game_type` (`game_type`),
  ADD KEY `idx_completed_at` (`completed_at`);

--
-- Indexes for table `user_streaks`
--
ALTER TABLE `user_streaks`
  ADD PRIMARY KEY (`streak_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  MODIFY `fib_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `findtheerror`
--
ALTER TABLE `findtheerror`
  MODIFY `error_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `fixthecode`
--
ALTER TABLE `fixthecode`
  MODIFY `fix_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `flashcardflipper`
--
ALTER TABLE `flashcardflipper`
  MODIFY `card_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `predictoutput`
--
ALTER TABLE `predictoutput`
  MODIFY `predict_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `quiz_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `treasurehunt`
--
ALTER TABLE `treasurehunt`
  MODIFY `hunt_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `truefalse`
--
ALTER TABLE `truefalse`
  MODIFY `tf_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  MODIFY `progress_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_streaks`
--
ALTER TABLE `user_streaks`
  MODIFY `streak_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_game_progress`
--
ALTER TABLE `user_game_progress`
  ADD CONSTRAINT `user_game_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_streaks`
--
ALTER TABLE `user_streaks`
  ADD CONSTRAINT `user_streaks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
