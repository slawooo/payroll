## Setup and utility commands

### Installation
* `./app start`
* `./app composer install`
* `./app console doctrine:schema:create`

### Testing
* `./app tests`
* `./app tests tests/Unit`
* `./app tests tests/Integration`
* `./app tests tests/Functional`

### CS Fixer
* `./app cscheck`
* `./app csfix`

### Other utility commands
* `./app console {your-command-here}`
* `./app shell` 
* `./app stop`

## Application commands (business use cases)
### Add department
* `./app console payroll:add-department HR fixed 100 7`
* `./app console payroll:add-department DevOps percentage 20 3`
### Add employee
* `./app console payroll:add-employee HR "Anna Hałerowa" "2020-05-15" 2499.99`
* `./app console payroll:add-employee DevOps "Admin Kowalski" "2024-03-10" 2999.99`
* `./app console payroll:add-employee DevOps "Anna Dewseniorska" "2022-07-22" 3999.99`
### Get report
* `./app console payroll:get-payroll-report`
* `./app console payroll:get-payroll-report --name=Anna --surname=Hałerowa`
* `./app console payroll:get-payroll-report --department=DevOps --order-by=total`


## Architecture notes

Although the task is small, I treated the domain as a miniature payroll system to show how I structure code for growth.

* The **core domain** is modelled with aggregates: `Department` (including bonus policy) and `Employee` plus value objects (`Money`).
* The **Application layer** exposes use cases via CQRS - commands: `AddDepartment`, `AddEmployee`, query: `GetPayrollReport`.
* The **Infrastructure layer** contains Doctrine repositories and mappings behind domain interfaces, separated from domain layer.
* The **UI layer** is implemented with Symfony Console commands (CLI), only talking to the Application layer.

Tests cover:

* domain logic: unit,
* application logic: unit, integration,
* persistence (handlers + Doctrine + DB): integration,
* CLI commands and report output: functional.

### Design trade-offs / missing pieces

* _Bonus_ and _total salary_ are stored as separate fields on `Employee`. They could be computed on the fly, but having them stored makes filtering/sorting the report much simpler and more efficient. The downside is that any future changes to salary/bonus logic must keep these fields in sync.
* I use a plain `DateTimeImmutable` in the domain model to calculate the years of work. In a real production system I’d inject a `Clock` (time provider) into a domain service instead, to make time‑based logic deterministic and easier to test, but here I keep the simpler approach to avoid over‑engineering.
* Currently, `Department` directly creates the appropriate `BonusCalculator` via `BonusCalculatorFactory`. For a larger system, it might be cleaner to push this into an `EmployeeFactory` (or domain service) and inject the calculator factory there. But I wanted to keep the example simple and to avoid "anemic entities".
* The `Money` value object internally stores amounts as integer cents (which is good for precision), but it still uses floats for some math operations (e.g. in `increaseByPercent`). In a production system, it would be better to fully avoid floats in monetary calculations and use `bcmath` (or a similar arbitrary‑precision approach) instead.
* Exception handling/logging is minimal in the CLI layer – in a real system you would wrap handlers, translate domain exceptions into user-friendly messages, and log errors.


## Task description

You can find the original task description here: [task.txt](doc/task.txt)
