# Installation
* use `app` script in terminal
* `./app start`
* `./app composer install`
* `./app console doctrine:schema:create`
* `./app console doctrine:schema:create --env=test`
* `./app console doctrine:schema:drop --force`

# Testing
* `./app tests`
* `./app tests tests/Unit`
* `./app tests tests/Integration`
* `./app tests tests/Functional`

# Usage
* `./app console {your-command-here}`
* `./app stop`

# Examples of custom commands
* `./app console payroll:add-department HR fixed 100 7`
* `./app console payroll:add-department DevOps percentage 20 3`

* `./app console payroll:add-employee HR Anna Hałerowa 5 2499.99`
* `./app console payroll:add-employee DevOps Admin Kowalski 1 2999.99`
* `./app console payroll:add-employee DevOps Anna Dewseniorska 3 3999.99`

* `./app console payroll:get-payroll-report`
* `./app console payroll:get-payroll-report --name=Anna --surname=Hałerowa`
* `./app console payroll:get-payroll-report --department=DevOps --order-by=total`
