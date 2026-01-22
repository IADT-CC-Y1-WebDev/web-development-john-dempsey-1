<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Static Members - PHP Classes &amp; Objects</title>
    <link rel="stylesheet" href="/examples/css/style.css">
    <link rel="stylesheet" href="/examples/css/prism-tomorrow.min.css">
</head>
<body>
    <div class="back-link">
        <a href="index.php">&larr; Back to Classes &amp; Objects</a>
    </div>

    <h1>Static Members</h1>

    <p>Static properties and methods belong to the class itself, not to individual objects. They are shared across all instances and can be accessed without creating an object.</p>

    <!-- Example 1 -->
    <h2>Static Properties</h2>
    <p>A static property is shared by all instances of a class. Use the <code>static</code> keyword to declare it, and <code>self::$property</code> to access it within the class.</p>
    <pre><code class="language-php">class Counter {
    private static $count = 0;

    public function __construct() {
        self::$count++;  // Increment shared counter
    }

    public static function getCount() {
        return self::$count;
    }
}

echo "Count: " . Counter::getCount() . "&lt;br&gt;";

$a = new Counter();
echo "Count: " . Counter::getCount() . "&lt;br&gt;";

$b = new Counter();
$c = new Counter();
echo "Count: " . Counter::getCount();</code></pre>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        class Counter {
            private static $count = 0;

            public function __construct() {
                self::$count++;
            }

            public static function getCount() {
                return self::$count;
            }
        }

        echo "Count: " . Counter::getCount() . "<br>";

        $a = new Counter();
        echo "Count: " . Counter::getCount() . "<br>";

        $b = new Counter();
        $c = new Counter();
        echo "Count: " . Counter::getCount();
        ?>
    </div>

    <!-- Example 2 -->
    <h2>Static Methods</h2>
    <p>Static methods can be called without creating an object, using <code>ClassName::methodName()</code>. They cannot access <code>$this</code> because there is no instance.</p>
    <pre><code class="language-php">class MathHelper {
    public static function add($a, $b) {
        return $a + $b;
    }

    public static function multiply($a, $b) {
        return $a * $b;
    }

    public static function percentage($amount, $percent) {
        return $amount * ($percent / 100);
    }
}

// Call static methods without creating an object
echo "5 + 3 = " . MathHelper::add(5, 3) . "&lt;br&gt;";
echo "5 x 3 = " . MathHelper::multiply(5, 3) . "&lt;br&gt;";
echo "20% of 150 = " . MathHelper::percentage(150, 20);</code></pre>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        class MathHelper {
            public static function add($a, $b) {
                return $a + $b;
            }

            public static function multiply($a, $b) {
                return $a * $b;
            }

            public static function percentage($amount, $percent) {
                return $amount * ($percent / 100);
            }
        }

        echo "5 + 3 = " . MathHelper::add(5, 3) . "<br>";
        echo "5 x 3 = " . MathHelper::multiply(5, 3) . "<br>";
        echo "20% of 150 = " . MathHelper::percentage(150, 20);
        ?>
    </div>

    <!-- Example 3 -->
    <h2>Registry Pattern: Tracking All Objects</h2>
    <p>A common use of static properties is to keep track of all created objects. This is called the Registry pattern.</p>
    <pre><code class="language-php">class BankAccount {
    private static $accounts = [];  // Shared across all instances

    protected $number;
    protected $name;
    protected $balance;

    public function __construct($num, $name, $bal) {
        $this->number = $num;
        $this->name = $name;
        $this->balance = $bal;

        // Register this account in the static array
        self::$accounts[$num] = $this;
    }

    public static function findAll() {
        return self::$accounts;
    }

    public static function findByNumber($num) {
        return self::$accounts[$num] ?? null;
    }

    public function __toString() {
        return "Account {$this->number}: {$this->name}, &euro;{$this->balance}";
    }
}

// Create some accounts
$acc1 = new BankAccount("111", "Alice", 100);
$acc2 = new BankAccount("222", "Bob", 200);
$acc3 = new BankAccount("333", "Charlie", 300);

// Find all accounts using static method
echo "&lt;strong&gt;All accounts:&lt;/strong&gt;&lt;br&gt;";
foreach (BankAccount::findAll() as $account) {
    echo $account . "&lt;br&gt;";
}

// Find specific account
echo "&lt;br&gt;&lt;strong&gt;Looking for account 222:&lt;/strong&gt;&lt;br&gt;";
$found = BankAccount::findByNumber("222");
echo $found;</code></pre>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        class BankAccountStatic {
            private static $accounts = [];

            protected $number;
            protected $name;
            protected $balance;

            public function __construct($num, $name, $bal) {
                $this->number = $num;
                $this->name = $name;
                $this->balance = $bal;

                self::$accounts[$num] = $this;
            }

            public static function findAll() {
                return self::$accounts;
            }

            public static function findByNumber($num) {
                return self::$accounts[$num] ?? null;
            }

            public function __toString() {
                return "Account {$this->number}: {$this->name}, &euro;{$this->balance}";
            }
        }

        $acc1 = new BankAccountStatic("111", "Alice", 100);
        $acc2 = new BankAccountStatic("222", "Bob", 200);
        $acc3 = new BankAccountStatic("333", "Charlie", 300);

        echo "<strong>All accounts:</strong><br>";
        foreach (BankAccountStatic::findAll() as $account) {
            echo $account . "<br>";
        }

        echo "<br><strong>Looking for account 222:</strong><br>";
        $found = BankAccountStatic::findByNumber("222");
        echo $found;
        ?>
    </div>

    <!-- Example 4 -->
    <h2>Using the v3 Classes</h2>
    <p>The v3 folder contains <code>BankAccount</code> with static members for tracking all accounts.</p>
    <pre><code class="language-php">require_once __DIR__ . '/classes/v3/SavingsAccount.php';
require_once __DIR__ . '/classes/v3/CurrentAccount.php';

// Create different account types
$savings = new SavingsAccount("S001", "Diana", 1000, 0.05);
$current = new CurrentAccount("C001", "Eve", 500);
$basic = new BankAccount("B001", "Frank", 250);

// All accounts are tracked in the parent class's static array
echo "&lt;strong&gt;All accounts (via BankAccount::findAll()):&lt;/strong&gt;&lt;br&gt;";
foreach (BankAccount::findAll() as $account) {
    echo $account . "&lt;br&gt;";
}</code></pre>

    <p class="output-label">Output:</p>
    <div class="output">
        <?php
        require_once __DIR__ . '/classes/v3/SavingsAccount.php';
        require_once __DIR__ . '/classes/v3/CurrentAccount.php';

        // Use v3 namespace by referencing the included classes
        $savings = new \SavingsAccount("S001", "Diana", 1000, 0.05);
        $current = new \CurrentAccount("C001", "Eve", 500);
        $basic = new \BankAccount("B001", "Frank", 250);

        echo "<strong>All accounts (via BankAccount::findAll()):</strong><br>";
        foreach (\BankAccount::findAll() as $account) {
            echo $account . "<br>";
        }
        ?>
    </div>

    <!-- Summary -->
    <h2>self:: vs $this</h2>
    <table style="width: 100%; border-collapse: collapse; margin: 1rem 0;">
        <tr style="background: #f5f5f5;">
            <th style="padding: 0.5rem; border: 1px solid #ddd; text-align: left;">Keyword</th>
            <th style="padding: 0.5rem; border: 1px solid #ddd; text-align: left;">Used For</th>
            <th style="padding: 0.5rem; border: 1px solid #ddd; text-align: left;">Example</th>
        </tr>
        <tr>
            <td style="padding: 0.5rem; border: 1px solid #ddd;"><code>$this</code></td>
            <td style="padding: 0.5rem; border: 1px solid #ddd;">Instance properties and methods</td>
            <td style="padding: 0.5rem; border: 1px solid #ddd;"><code>$this->balance</code></td>
        </tr>
        <tr>
            <td style="padding: 0.5rem; border: 1px solid #ddd;"><code>self::</code></td>
            <td style="padding: 0.5rem; border: 1px solid #ddd;">Static properties and methods</td>
            <td style="padding: 0.5rem; border: 1px solid #ddd;"><code>self::$accounts</code></td>
        </tr>
    </table>

    <script src="/examples/js/prism-core.min.js"></script>
    <script src="/examples/js/prism-autoloader.min.js" data-autoloader-path="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/"></script>
</body>
</html>
