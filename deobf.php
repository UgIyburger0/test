<?php

$credits = "
--made by galaxyfounded
";

// Set the output file
$output = "yes.txt";

// Function to append content to a file
function appendToFile($filename, $content) {
    if (!file_exists($filename)) {
        file_put_contents($filename, $GLOBALS['credits'] . "\n" . $content);
    } else {
        file_put_contents($filename, $content, FILE_APPEND);
    }
}

// Function to format arrays
function formatArray($array) {
    $result = "{";
    foreach ($array as $index => $value) {
        if (is_array($value)) {
            $result .= formatArray($value);
        } else {
            $result .= "\n'Index: $index' | 'Value: $value', ";
        }
    }
    $result = rtrim($result, ', ') . "\n},\n";
    return $result;
}

// Function to handle each array element
function foreachArray($array) {
    if (is_array($array)) {
        foreach ($array as $index => $value) {
            if (is_array($value)) {
                appendToFile($GLOBALS['output'], formatArray($value) . "\n");
            } else {
                appendToFile($GLOBALS['output'], "{Index: $index | Value: $value\n");
            }
        }
    }
}

// Function to unpack data
function unpack(...$args) {
    $data = $args;
    if (is_array($data) && count($data) > 0) {
        foreachArray($data);
    }
    return $data;
}

// Check if 'code' parameter is set in the URL
if (isset($_GET['code'])) {
    // Get the code from the URL and decode it
    $encodedCode = $_GET['code'];
    // For security, decode and escape the input
    $decodedCode = htmlspecialchars_decode($encodedCode, ENT_QUOTES);

    // Assuming the provided code is a JSON-like string for this example
    // In practice, you might be evaluating Lua or other scripts, ensure security
    $data = json_decode($decodedCode, true); // Decoding as an associative array

    // Call unpack with the decoded data
    unpack($data);

    echo "Data processed and written to $output.";
} else {
    echo "No code provided!";
}
?>
