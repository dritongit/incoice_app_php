<?php
class Router {
    protected static $routes = [];

    public static function add($method, $route, $callback, $middleware = null) {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        self::$routes[] = [
            'method'   => strtoupper($method),
            'route'    => $route,
            'pattern'  => $pattern,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    // public static function dispatch($method, $uri) {
    //     echo $method . $uri;
    //     foreach (self::$routes as $route) {
    //         if ($route['method'] === strtoupper($method) && preg_match($route['pattern'], $uri, $matches)) {
    //             array_shift($matches); // Remove full match

    //             // If middleware exists, execute it
    //             if ($route['middleware'] !== null) {
    //                 $middlewareResult = call_user_func($route['middleware']);

    //                 // If middleware fails (returns null or exits), stop execution
    //                 if (!$middlewareResult) {
    //                     exit; // Stops further execution, ensuring unauthorized access is blocked
    //                 }

    //                 // Pass the result of middleware (token data) to the controller
    //                 array_unshift($matches, $middlewareResult);
    //             }

    //             return call_user_func_array($route['callback'], $matches);
    //         }
    //     }

    //     // If no route matches, return 404
    //     http_response_code(404);
    //     echo json_encode(["error" => "Route not found"]);
    //     exit;
    // }

    public static function dispatch($method, $uri) {
        // Normalize the URI
        $uri = trim($uri, '/');
    
        foreach (self::$routes as $route) {
            if ($route['method'] === strtoupper($method) && preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches); // Remove full match
    
                // Execute middleware if exists
                if ($route['middleware'] !== null) {
                    $middlewareResult = call_user_func($route['middleware']);
    
                    if (!$middlewareResult) {
                        exit; // Stops execution if middleware fails
                    }
    
                    array_unshift($matches, $middlewareResult);
                }
    
                return call_user_func_array($route['callback'], $matches);
            }
        }
    
        // Debugging: Print which route is actually received
        http_response_code(404);
        echo json_encode(["error" => "Route not found", "route_received" => $uri]);
        exit;
    }
    
}

?>
