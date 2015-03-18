<?php 

  require_once('lib/sanitize.php');

  class SanitizeTest extends PHPUnit_Framework_TestCase
  {
    public function test_injection()
    {
      $injection_input = "SELECT fieldlist FROM table WHERE field = 'anyting' OR 'x' = 'x'";
      $expected_sanitized_output = "SELECT fieldlist FROM table WHERE field = \'anyting\' OR \'x\' = \'x\'";

      $sanitized_output = sanitize($injection_input);

      $this->assertTrue($sanitized_output == $expected_sanitized_output, 'true');
    }

    public function test_tags()
    {
      $tag_input = "<h1>This is a test</h1>";
      $expected_sanitized_output = "This is a test";

      $sanitized_output = sanitize($tag_input);

      $this->assertTrue($sanitized_output == $expected_sanitized_output, 'true');
    }

  }
 ?>
