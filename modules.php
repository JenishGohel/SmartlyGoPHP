<?php
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$user_fullname = $_SESSION['user_name'] ?? 'Guest Learner';


$modules = [
    [
        'id' => 1,
        'chapter' => 'Chapter 1',
        'title' => 'Introduction to PHP',
        'content' => '
            <h3>What is PHP?</h3>
            <p>PHP (Hypertext Preprocessor) is a powerful server-side scripting language designed for web development. It was created by Rasmus Lerdorf in 1994 and has become one of the most popular languages for building dynamic websites.</p>
            
            <h3>Why Learn PHP?</h3>
            <ul>
                <li>🌐 <strong>Widely Used:</strong> Powers 77% of websites including Facebook, WordPress, and Wikipedia</li>
                <li>🚀 <strong>Easy to Learn:</strong> Simple syntax, perfect for beginners</li>
                <li>💰 <strong>Great Career:</strong> High demand for PHP developers worldwide</li>
                <li>🔧 <strong>Versatile:</strong> Can build anything from simple forms to complex applications</li>
            </ul>
            
            <h3>How PHP Works</h3>
            <p>PHP runs on the server side. When a user requests a PHP page:</p>
            <ol>
                <li>The server processes the PHP code</li>
                <li>Generates HTML</li>
                <li>Sends the result to the browser</li>
            </ol>
            
            <div class="code-example">
                <h4>Your First PHP Code:</h4>
                <pre>&lt;?php
echo "Hello, World!";
?&gt;</pre>
            </div>
        '
    ],
    [
        'id' => 2,
        'chapter' => 'Chapter 2',
        'title' => 'PHP Basics & Syntax',
        'content' => '
            <h3>PHP Tags</h3>
            <p>PHP code is enclosed within special tags:</p>
            <div class="code-example">
                <pre>&lt;?php
// Your PHP code here
?&gt;</pre>
            </div>
            
            <h3>Comments</h3>
            <p>Comments help document your code:</p>
            <div class="code-example">
                <pre>// Single line comment

/* Multi-line
   comment */
   
# Another single line comment</pre>
            </div>
            
            <h3>Echo and Print</h3>
            <p>Output text to the browser:</p>
            <div class="code-example">
                <pre>echo "Hello World!";
print "Hello PHP!";</pre>
            </div>
            
            <h3>Statements</h3>
            <p>Every PHP statement must end with a semicolon (;)</p>
            <div class="code-example">
                <pre>echo "Statement 1";
echo "Statement 2";
echo "Statement 3";</pre>
            </div>
        '
    ],
    [
        'id' => 3,
        'chapter' => 'Chapter 3',
        'title' => 'Variables & Data Types',
        'content' => '
            <h3>Variables</h3>
            <p>Variables store data values. In PHP, they start with a <strong>$</strong> sign:</p>
            <div class="code-example">
                <pre>$name = "John";
$age = 25;
$price = 19.99;
$isActive = true;</pre>
            </div>
            
            <h3>Variable Rules</h3>
            <ul>
                <li>✅ Must start with $ symbol</li>
                <li>✅ Can contain letters, numbers, underscore</li>
                <li>✅ Must start with letter or underscore (after $)</li>
                <li>❌ Cannot start with a number</li>
                <li>✅ Case-sensitive ($name and $Name are different)</li>
            </ul>
            
            <h3>Data Types</h3>
            <table class="data-table">
                <tr><th>Type</th><th>Example</th><th>Description</th></tr>
                <tr><td>String</td><td>"Hello"</td><td>Text data</td></tr>
                <tr><td>Integer</td><td>42</td><td>Whole numbers</td></tr>
                <tr><td>Float</td><td>3.14</td><td>Decimal numbers</td></tr>
                <tr><td>Boolean</td><td>true/false</td><td>True or False</td></tr>
                <tr><td>Array</td><td>[1,2,3]</td><td>Multiple values</td></tr>
                <tr><td>NULL</td><td>null</td><td>No value</td></tr>
            </table>
        '
    ],
    [
        'id' => 4,
        'chapter' => 'Chapter 4',
        'title' => 'Operators',
        'content' => '
            <h3>Arithmetic Operators</h3>
            <div class="code-example">
                <pre>$a = 10;
$b = 3;

echo $a + $b;  // Addition: 13
echo $a - $b;  // Subtraction: 7
echo $a * $b;  // Multiplication: 30
echo $a / $b;  // Division: 3.33
echo $a % $b;  // Modulus: 1</pre>
            </div>
            
            <h3>Assignment Operators</h3>
            <div class="code-example">
                <pre>$x = 10;
$x += 5;  // Same as: $x = $x + 5
$x -= 3;  // Same as: $x = $x - 3
$x *= 2;  // Same as: $x = $x * 2</pre>
            </div>
            
            <h3>Comparison Operators</h3>
            <table class="data-table">
                <tr><th>Operator</th><th>Meaning</th><th>Example</th></tr>
                <tr><td>==</td><td>Equal to</td><td>$a == $b</td></tr>
                <tr><td>===</td><td>Identical</td><td>$a === $b</td></tr>
                <tr><td>!=</td><td>Not equal</td><td>$a != $b</td></tr>
                <tr><td>&gt;</td><td>Greater than</td><td>$a &gt; $b</td></tr>
                <tr><td>&lt;</td><td>Less than</td><td>$a &lt; $b</td></tr>
            </table>
            
            <h3>Logical Operators</h3>
            <div class="code-example">
                <pre>$x = 10;
$y = 5;

// AND - both must be true
if ($x > 5 && $y < 10) { }

// OR - at least one must be true
if ($x > 15 || $y < 10) { }

// NOT - reverse the result
if (!($x == $y)) { }</pre>
            </div>
        '
    ],
    [
        'id' => 5,
        'chapter' => 'Chapter 5',
        'title' => 'Control Structures',
        'content' => '
            <h3>If Statement</h3>
            <div class="code-example">
                <pre>$age = 18;

if ($age >= 18) {
    echo "You are an adult";
} elseif ($age >= 13) {
    echo "You are a teenager";
} else {
    echo "You are a child";
}</pre>
            </div>
            
            <h3>Switch Statement</h3>
            <div class="code-example">
                <pre>$day = "Monday";

switch ($day) {
    case "Monday":
        echo "Start of the week!";
        break;
    case "Friday":
        echo "Almost weekend!";
        break;
    default:
        echo "Regular day";
}</pre>
            </div>
            
            <h3>Ternary Operator</h3>
            <p>A shorthand for simple if-else:</p>
            <div class="code-example">
                <pre>$age = 20;
$status = ($age >= 18) ? "Adult" : "Minor";
echo $status;  // Output: Adult</pre>
            </div>
        '
    ],
    [
        'id' => 6,
        'chapter' => 'Chapter 6',
        'title' => 'Loops',
        'content' => '
            <h3>For Loop</h3>
            <p>When you know how many times to loop:</p>
            <div class="code-example">
                <pre>for ($i = 1; $i <= 5; $i++) {
    echo "Number: $i<br>";
}

// Output:
// Number: 1
// Number: 2
// Number: 3
// Number: 4
// Number: 5</pre>
            </div>
            
            <h3>While Loop</h3>
            <p>Loop while condition is true:</p>
            <div class="code-example">
                <pre>$count = 1;

while ($count <= 5) {
    echo $count;
    $count++;
}</pre>
            </div>
            
            <h3>Do-While Loop</h3>
            <p>Execute at least once, then check condition:</p>
            <div class="code-example">
                <pre>$num = 1;

do {
    echo $num;
    $num++;
} while ($num <= 5);</pre>
            </div>
            
            <h3>Foreach Loop</h3>
            <p>Loop through arrays:</p>
            <div class="code-example">
                <pre>$colors = ["red", "green", "blue"];

foreach ($colors as $color) {
    echo $color . "<br>";
}</pre>
            </div>
            
            <h3>Break & Continue</h3>
            <div class="code-example">
                <pre>// Break - exit the loop
for ($i = 1; $i <= 10; $i++) {
    if ($i == 5) break;
    echo $i;
}

// Continue - skip current iteration
for ($i = 1; $i <= 5; $i++) {
    if ($i == 3) continue;
    echo $i;
}</pre>
            </div>
        '
    ],
    [
        'id' => 7,
        'chapter' => 'Chapter 7',
        'title' => 'Arrays',
        'content' => '
            <h3>Creating Arrays</h3>
            <div class="code-example">
                <pre>// Indexed Array
$fruits = ["Apple", "Banana", "Orange"];
$numbers = array(1, 2, 3, 4, 5);

// Associative Array
$person = [
    "name" => "John",
    "age" => 25,
    "city" => "New York"
];</pre>
            </div>
            
            <h3>Accessing Array Elements</h3>
            <div class="code-example">
                <pre>echo $fruits[0];        // Apple
echo $person["name"];   // John</pre>
            </div>
            
            <h3>Array Functions</h3>
            <table class="data-table">
                <tr><th>Function</th><th>Description</th></tr>
                <tr><td>count($arr)</td><td>Get array length</td></tr>
                <tr><td>array_push($arr, $val)</td><td>Add to end</td></tr>
                <tr><td>array_pop($arr)</td><td>Remove from end</td></tr>
                <tr><td>array_merge($arr1, $arr2)</td><td>Merge arrays</td></tr>
                <tr><td>in_array($val, $arr)</td><td>Check if exists</td></tr>
                <tr><td>sort($arr)</td><td>Sort ascending</td></tr>
            </table>
            
            <h3>Multidimensional Arrays</h3>
            <div class="code-example">
                <pre>$students = [
    ["John", 85, "A"],
    ["Sarah", 92, "A+"],
    ["Mike", 78, "B"]
];

echo $students[0][0];  // John
echo $students[1][1];  // 92</pre>
            </div>
        '
    ],
    [
        'id' => 8,
        'chapter' => 'Chapter 8',
        'title' => 'Functions',
        'content' => '
            <h3>Creating Functions</h3>
            <div class="code-example">
                <pre>function greet() {
    echo "Hello, World!";
}

greet();  // Call the function</pre>
            </div>
            
            <h3>Function Parameters</h3>
            <div class="code-example">
                <pre>function greet($name) {
    echo "Hello, $name!";
}

greet("John");  // Hello, John!</pre>
            </div>
            
            <h3>Return Values</h3>
            <div class="code-example">
                <pre>function add($a, $b) {
    return $a + $b;
}

$result = add(5, 3);
echo $result;  // 8</pre>
            </div>
            
            <h3>Default Parameters</h3>
            <div class="code-example">
                <pre>function greet($name = "Guest") {
    echo "Hello, $name!";
}

greet();         // Hello, Guest!
greet("John");   // Hello, John!</pre>
            </div>
            
            <h3>Variable Scope</h3>
            <div class="code-example">
                <pre>$global_var = "I am global";

function test() {
    $local_var = "I am local";
    global $global_var;  // Access global variable
    echo $global_var;
}

test();</pre>
            </div>
        '
    ],
    [
        'id' => 9,
        'chapter' => 'Chapter 9',
        'title' => 'Superglobals & Forms',
        'content' => '
            <h3>Superglobal Arrays</h3>
            <p>Special variables available everywhere:</p>
            <ul>
                <li><strong>$_GET</strong> - URL parameters</li>
                <li><strong>$_POST</strong> - Form data</li>
                <li><strong>$_SESSION</strong> - Session variables</li>
                <li><strong>$_COOKIE</strong> - Cookie data</li>
                <li><strong>$_SERVER</strong> - Server information</li>
                <li><strong>$_FILES</strong> - Uploaded files</li>
            </ul>
            
            <h3>HTML Form with POST</h3>
            <div class="code-example">
                <pre>&lt;form method="POST" action="process.php"&gt;
    &lt;input type="text" name="username"&gt;
    &lt;input type="password" name="password"&gt;
    &lt;button type="submit"&gt;Login&lt;/button&gt;
&lt;/form&gt;</pre>
            </div>
            
            <h3>Processing Form Data</h3>
            <div class="code-example">
                <pre>if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    echo "Welcome, $username!";
}</pre>
            </div>
            
            <h3>Sessions</h3>
            <div class="code-example">
                <pre>// Start session
session_start();

// Store data
$_SESSION["username"] = "John";

// Retrieve data
echo $_SESSION["username"];

// Destroy session
session_destroy();</pre>
            </div>
        '
    ],
    [
        'id' => 10,
        'chapter' => 'Chapter 10',
        'title' => 'Working with Files',
        'content' => '
            <h3>Reading Files</h3>
            <div class="code-example">
                <pre>// Read entire file
$content = file_get_contents("file.txt");
echo $content;

// Read line by line
$file = fopen("file.txt", "r");
while (!feof($file)) {
    $line = fgets($file);
    echo $line;
}
fclose($file);</pre>
            </div>
            
            <h3>Writing to Files</h3>
            <div class="code-example">
                <pre>// Write (overwrite)
file_put_contents("file.txt", "Hello World");

// Append
$file = fopen("file.txt", "a");
fwrite($file, "New line\n");
fclose($file);</pre>
            </div>
            
            <h3>File Operations</h3>
            <table class="data-table">
                <tr><th>Function</th><th>Description</th></tr>
                <tr><td>file_exists()</td><td>Check if file exists</td></tr>
                <tr><td>unlink()</td><td>Delete file</td></tr>
                <tr><td>copy()</td><td>Copy file</td></tr>
                <tr><td>rename()</td><td>Rename/move file</td></tr>
                <tr><td>filesize()</td><td>Get file size</td></tr>
            </table>
        '
    ],
    [
        'id' => 11,
        'chapter' => 'Chapter 11',
        'title' => 'MySQL & Databases',
        'content' => '
            <h3>Connecting to MySQL</h3>
            <div class="code-example">
                <pre>$host = "localhost";
$user = "root";
$pass = "";
$db = "mydb";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed");
}</pre>
            </div>
            
            <h3>SELECT Query</h3>
            <div class="code-example">
                <pre>$sql = "SELECT * FROM users WHERE age > 18";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row["name"];
}</pre>
            </div>
            
            <h3>INSERT Query</h3>
            <div class="code-example">
                <pre>$name = "John";
$email = "john@example.com";

$sql = "INSERT INTO users (name, email) 
        VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $email);
$stmt->execute();</pre>
            </div>
            
            <h3>UPDATE Query</h3>
            <div class="code-example">
                <pre>$sql = "UPDATE users 
        SET email = ? 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $email, $id);
$stmt->execute();</pre>
            </div>
            
            <h3>DELETE Query</h3>
            <div class="code-example">
                <pre>$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();</pre>
            </div>
        '
    ],
    [
        'id' => 12,
        'chapter' => 'Chapter 12',
        'title' => 'OOP - Object Oriented PHP',
        'content' => '
            <h3>Creating Classes</h3>
            <div class="code-example">
                <pre>class Person {
    public $name;
    public $age;
    
    public function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
    
    public function greet() {
        return "Hello, I am $this->name";
    }
}

$person = new Person("John", 25);
echo $person->greet();</pre>
            </div>
            
            <h3>Inheritance</h3>
            <div class="code-example">
                <pre>class Student extends Person {
    public $grade;
    
    public function study() {
        return "$this->name is studying";
    }
}

$student = new Student("Sarah", 20);
$student->grade = "A";
echo $student->greet();
echo $student->study();</pre>
            </div>
            
            <h3>Access Modifiers</h3>
            <ul>
                <li><strong>public</strong> - Accessible everywhere</li>
                <li><strong>private</strong> - Only within the class</li>
                <li><strong>protected</strong> - Within class and child classes</li>
            </ul>
            
            <div class="code-example">
                <pre>class BankAccount {
    private $balance = 0;
    
    public function deposit($amount) {
        $this->balance += $amount;
    }
    
    public function getBalance() {
        return $this->balance;
    }
}</pre>
            </div>
        '
    ]
];

// Get current page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max(1, min($current_page, count($modules)));
$current_module = $modules[$current_page - 1];
$total_pages = count($modules);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Modules - SmartlyGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
       /* =========================
   GLOBAL PAGE STYLE
========================= */
body {
    background: linear-gradient(135deg, #fef3f7 0%, #e9d5ff 50%, #ddd6fe 100%);
    min-height: 100vh;
    font-family: 'Georgia', serif;
}

/* =========================
   BOOK LAYOUT
========================= */
.book-container {
    perspective: 2000px;
    max-width: 1200px;
    margin: 0 auto;
}

.book {
    background: #f9f9f9;
    border-radius: 10px;
    box-shadow:
        0 10px 40px rgba(0,0,0,0.2),
        inset 0 0 20px rgba(0,0,0,0.05);
    position: relative;
    transition: all 0.3s;
}

.page {
    padding: 60px;
    min-height: 600px;
    background: linear-gradient(
        to right,
        #fefefe 0%,
        #ffffff 50%,
        #fefefe 100%
    );
}

/* =========================
   CONTENT STYLING
========================= */
.page-content h3 {
    color: #a855f7;
    font-size: 1.8rem;
    font-weight: bold;
    margin: 30px 0 15px;
    border-bottom: 2px solid #a855f7;
    padding-bottom: 10px;
}

.page-content p {
    line-height: 1.8;
    color: #333;
    margin: 15px 0;
    font-size: 1.1rem;
}

.page-content ul,
.page-content ol {
    margin: 15px 0;
    padding-left: 30px;
}

.page-content li {
    margin: 10px 0;
    line-height: 1.6;
    color: #444;
}

/* =========================
   CODE BLOCK
========================= */
.code-example {
    background: #2d3748;
    border-radius: 8px;
    padding: 20px;
    margin: 20px 0;
    border-left: 4px solid #a855f7;
}

.code-example h4 {
    color: #ffffff;
    margin-bottom: 10px;
    font-size: 1rem;
}

.code-example pre {
    color: #e2e8f0;
    font-family: 'Courier New', monospace;
    line-height: 1.6;
    overflow-x: auto;
    white-space: pre-wrap;
}

/* =========================
   TABLES
========================= */
.data-table {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.data-table th {
    background: #a855f7;
    color: white;
    padding: 12px;
    text-align: left; /* FIXED */
    font-weight: bold;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.data-table tr:hover {
    background: #f9fafb;
}

/* =========================
   TITLES
========================= */
.chapter-title {
    color: #ec4899;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 10px;
}

.main-title {
    color: #1f2937;
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 30px;
}

/* =========================
   PAGE NUMBER
========================= */
.page-number {
    position: absolute;
    bottom: 30px;
    right: 60px;
    color: #999;
    font-style: italic;
    font-family: 'Times New Roman', serif;
}

/* =========================
   NAVIGATION BUTTONS
========================= */
.nav-button {
    background: linear-gradient(135deg, #a855f7, #ec4899);
    color: white;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: bold;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.nav-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(168, 85, 247, 0.4);
}

.nav-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* =========================
   PAGE FLIP
========================= */
@keyframes pageFlip {
    0% { transform: rotateY(0deg); }
    100% { transform: rotateY(-180deg); }
}

.page-flip {
    animation: pageFlip 0.6s ease-in-out;
}

/* =================================================
   🔒 NAVBAR CSS (EXACT COPY FROM DASHBOARD)
================================================= */
.header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.gradient-text {
    background: linear-gradient(135deg, #a855f7, #ec4899, #f59e0b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.header * {
    font-family: ui-sans-serif, system-ui, -apple-system,
                 BlinkMacSystemFont, "Segoe UI",
                 Roboto, Helvetica, Arial,
                 "Apple Color Emoji", "Segoe UI Emoji",
                 "Segoe UI Symbol", sans-serif;
}

.login-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: .8;
    }
}

    </style>
</head>
<body>
    
    <!-- Header Navigation -->
    <header class="header fixed top-0 left-0 right-0 z-50 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-3xl">🏠</span>
            <h1 class="text-2xl font-bold gradient-text">SmartlyGoPHP</h1>
            </div>
            
            <nav class="hidden md:flex space-x-6 items-center">
                <a href="dashboard.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">🏠 Home</a>
                <a href="tools.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">🛠️ Tools</a>
                <a href="modules.php" class="text-purple-600 font-semibold">📚 Modules</a>
                <a href="feedback.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">💬 Feedback</a>
                <a href="contact.php" class="text-gray-700 hover:text-purple-600 font-semibold transition">📧 Contact</a>
                
                <?php if ($user_id > 0): ?>
                    <a href="logout.php" 
                       class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all font-semibold" 
                       onclick="return confirm('Are you sure you want to logout?')">
                       🚪 Logout
                    </a>
                <?php else: ?>
                    <a href="login.php" 
                       class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all font-semibold login-pulse shadow-lg">
                       🔐 Login
                    </a>
                <?php endif; ?>
            </nav>
            
            <!-- Mobile Menu Button -->
            <button class="md:hidden text-3xl text-purple-600" onclick="toggleMobileMenu()">☰</button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-2">
            <a href="dashboard.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">🏠 Home</a>
            <a href="tools.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">🛠️ Tools</a>
            <a href="modules.php" class="block px-4 py-2 bg-purple-100 rounded-lg">📚 Modules</a>
            <a href="feedback.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">💬 Feedback</a>
            <a href="contact.php" class="block px-4 py-2 rounded-lg hover:bg-purple-100 transition">📧 Contact</a>
            
            <?php if ($user_id > 0): ?>
                <a href="logout.php" 
                   class="block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition" 
                   onclick="return confirm('Are you sure you want to logout?')">
                   🚪 Logout
                </a>
            <?php else: ?>
                <a href="login.php" 
                   class="block px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition">
                   🔐 Login
                </a>
            <?php endif; ?>
        </div>
    </header>
    
    <!-- Main Content -->
    <main class="pt-28 pb-12 px-4">
        <div class="book-container">
            
            <!-- Book -->
            <div class="book">
                <div class="page">
                    
                    <!-- Chapter Info -->
                    <div class="chapter-title"><?php echo $current_module['chapter']; ?></div>
                    <h1 class="main-title"><?php echo $current_module['title']; ?></h1>
                    
                    <!-- Content -->
                    <div class="page-content">
                        <?php echo $current_module['content']; ?>
                    </div>
                    
                    <!-- Page Number -->
                    <div class="page-number">Page <?php echo $current_page; ?> of <?php echo $total_pages; ?></div>
                    
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="flex justify-between items-center mt-8">
                <a href="?page=<?php echo max(1, $current_page - 1); ?>">
                    <button class="nav-button" <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>>
                        ← Previous Chapter
                    </button>
                </a>
                
                <div class="text-center">
                    <div class="text-gray-600 mb-2">Chapter <?php echo $current_page; ?> of <?php echo $total_pages; ?></div>
                    <div class="text-sm text-gray-500">Welcome, <?php echo htmlspecialchars($user_fullname); ?>!</div>
                </div>
                
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>">
                    <button class="nav-button" <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>>
                        Next Chapter →
                    </button>
                </a>
            </div>

            <!-- Chapter Index -->
            <div class="mt-8 bg-white bg-opacity-90 backdrop-blur-lg rounded-xl p-6 shadow-xl">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📖 Table of Contents</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php foreach ($modules as $module): ?>
                        <a href="?page=<?php echo $module['id']; ?>" 
                           class="p-3 rounded-lg <?php echo ($module['id'] == $current_page) ? 'bg-purple-100 border-2 border-purple-500' : 'bg-gray-50 hover:bg-purple-50'; ?> transition">
                            <div class="text-sm text-purple-600 font-semibold"><?php echo $module['chapter']; ?></div>
                            <div class="text-sm text-gray-700"><?php echo $module['title']; ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div class="text-center mt-8">
                <a href="dashboard.php" class="inline-block bg-white bg-opacity-80 hover:bg-opacity-100 text-purple-600 font-semibold py-3 px-8 rounded-lg transition shadow-lg">
                    ← Back to Dashboard
                </a>
            </div>

        </div>
    </main>
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('mobileMenu');
            const toggle = event.target.closest('button');
            
            if (!menu.contains(event.target) && !toggle && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });
        
        // Add page flip animation
        document.querySelectorAll('.nav-button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelector('.page').classList.add('page-flip');
            });
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft' && <?php echo $current_page; ?> > 1) {
                window.location.href = '?page=<?php echo max(1, $current_page - 1); ?>';
            }
            if (e.key === 'ArrowRight' && <?php echo $current_page; ?> < <?php echo $total_pages; ?>) {
                window.location.href = '?page=<?php echo min($total_pages, $current_page + 1); ?>';
            }
        });
    </script>
    
</body>
</html>