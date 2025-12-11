<?php

use App\Http\Middleware\XSSProtection;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;

class XSSProtectionTest extends TestCase
{
    public function testXSSProtection()
    {
        // Arrange
        $input = [
            'name' => '<script>alert("XSS attack!");</script>',
            'email' => 'user@example.com',
            'password' => '<strong>password123</strong>',
        ];
        $request = new Request($input);

        $closure = function (Request $request) {
            return $request;
        };

        $middleware = new XSSProtection();

        // Act
        $response = $middleware->handle($request, $closure);

        // Assert
        $output = $response->all();
        $this->assertEquals('user@example.com', $output['email']);
        $this->assertEquals('password123', $output['password']);
        $this->assertEquals('alert("XSS attack!");', $output['name']);
    }


    
    
}
