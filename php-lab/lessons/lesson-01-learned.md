# PHP — Lesson 1
## Language fundamentals and first dynamic page

[English](lesson-01-learned.md) | [Italiano](lesson-01-learned.it.md)

## 1. Lesson objective

Reconstruct the first recovered PHP lesson and independently reproduce its core concepts with PHP 8.3.6.

The recovered teacher snapshot is historical evidence of what was shown in class. The files under `php-lab/exercises/lesson-01/` are our own reproduction and verification.

## 2. Evidence boundary

The recovered lesson snapshot showed examples involving:

- variables and assignment;
- swapping two values through a temporary variable;
- arithmetic and `number_format()`;
- associative arrays;
- constants declared with `define()` and `const`;
- `PHP_VERSION` and `__DIR__`;
- arrays of products rendered with `foreach`;
- PHP embedded in HTML and `<?= ?>`;
- a `for` loop that skips multiples of three;
- nested associative arrays;
- `foreach` key/value iteration;
- a GET form;
- `$_GET`;
- an `if`/`else` majority check.

These observations establish the reconstruction target. They do not by themselves prove local reproduction.

## 3. Local reproduction

Five independent exercises were created:

1. `01-variables.php` — variables, temporary swap, arithmetic, and number formatting.
2. `02-array-constants.php` — associative arrays, derived values, constants, `PHP_VERSION`, and `__DIR__`.
3. `03-products-table.php` — an array of products rendered as an HTML table with `foreach` and short echo syntax.
4. `04-loops-nested-arrays.php` — `for`, modulo, `continue`, nested associative arrays, and key/value iteration.
5. `05-age-form.php` — a GET form, `$_GET`, null coalescing, and conditional rendering.

The reproduction deliberately demonstrates the recovered concepts without treating the teacher snapshot as our implementation.

## 4. Verification

All five PHP files passed `php -l`.

The CLI exercises were executed and checked against expected behavior:

- the two numeric variables were swapped correctly;
- the calculated total was `37.50`;
- the associative product total was `99.80`;
- the product table rendered the expected products and totals;
- multiples of three were excluded from the numeric loop;
- nested city data was rendered correctly.

The GET exercise was also verified through the PHP development server bound to `127.0.0.1`.

Observed HTTP behavior:

- a request without `age` rendered the form without a result;
- `?age=17` rendered `Result: minor`;
- `?age=18` rendered `Result: adult`;
- the PHP server log contained no PHP warning, fatal error, parse error, or deprecation during the verification.

The development server was stopped after the test and was no longer reachable on the verification port.

## 5. Lesson Learned

### 1. PHP variables use the `$` prefix

Values can be assigned and subsequently changed during execution.

### 2. Arrays can model structured data

Associative arrays map meaningful keys to values, and nested arrays can represent more complex records.

### 3. PHP provides two forms of user-defined constants

Both `define()` and `const` were reproduced, together with predefined/runtime values such as `PHP_VERSION` and the magic constant `__DIR__`.

### 4. Loops and conditionals control execution

`for`, `foreach`, `if`, modulo, and `continue` were exercised with deterministic behavior checks.

### 5. PHP can generate HTML dynamically

PHP expressions and control structures can be embedded in an HTML document. The short echo syntax `<?= ?>` was used to render values in table cells.

### 6. Query-string data is available through `$_GET`

A real HTTP request to the local PHP development server demonstrated that the page receives the `age` query parameter and selects the appropriate conditional branch.

### 7. Reconstruction and reproduction are different claims

Recovered teacher material tells us what was present in the lesson snapshot. Completion in this lab requires our own implementation, execution, and verification.

## 6. Final lesson state

PHP 1 is reconstructed, locally reproduced, run, and verified.

`PHP_1_LOCAL_REPRODUCTION=PASS`
