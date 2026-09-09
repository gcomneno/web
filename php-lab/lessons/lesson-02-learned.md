# PHP — Lesson 2
## Static catalog layout as a bridge to dynamic rendering

[English](lesson-02-learned.md) | [Italiano](lesson-02-learned.it.md)

## 1. Lesson objective

Reconstruct the second recovered PHP lesson from the available static catalog artifact and independently reproduce its structural concepts.

The recovered `prodotti.html` file is evidence of the available lesson artifact. The file under `php-lab/exercises/lesson-02/` is our own reproduction and verification.

## 2. Evidence boundary

The recovered artifact showed a completely static HTML catalog using Bootstrap 5.3.3.

Observed structure included:

- a main Bootstrap container;
- a header with a logo and horizontal navigation;
- five horizontal navigation entries;
- a left sidebar with five categories;
- a main product area;
- twelve static product cards;
- three product columns per row at the Bootstrap `md` breakpoint;
- static pagination with pages `1`, `2`, and `3`;
- a footer;
- Bootstrap CSS and JavaScript loaded from a CDN.

The artifact contained no PHP tags, `$_GET`, `$_POST`, `foreach`, `include`, `require`, PDO, or SQL.

The artifact therefore supports reconstruction of the static catalog structure. It does not prove everything that may have been explained or performed in the classroom.

## 3. Local reproduction

One independent static catalog was created:

1. `catalog.html` — Bootstrap layout with header/navigation, category sidebar, twelve product cards, static pagination, and footer.

The reproduction intentionally remains static.

It does not introduce PHP, database access, dynamic loops, includes, or dynamic pagination before those concepts are supported by the recovered evidence for later lessons.

The implementation uses different content from the teacher artifact while preserving the structural learning target.

## 4. Verification

The generated file was checked structurally.

Observed local results:

- `CARD_COUNT=12`;
- `PRODUCT_COLUMN_COUNT=12`;
- `CATEGORY_COUNT=5`;
- `PAGE_ITEM_COUNT=3`;
- `PHP_2_STATIC_BOUNDARY=PASS`.

The catalog was then served through the PHP 8.3.6 development server bound to `127.0.0.1:18082`.

A real HTTP request to `/catalog.html` returned `HTTP_CODE=200`.

The HTTP response contained:

- twelve product cards;
- twelve product titles;
- five category entries;
- three pagination entries;
- both `Product 1` and `Product 12`.

The resulting gates were:

- `PHP_2_PRODUCT_RANGE=PASS`;
- `PHP_2_HTTP_RENDERING=PASS`;
- `PHP_2_SERVER_CLEANUP=PASS`.

The development server was stopped after verification.

## 5. Lesson Learned

### 1. A dynamic application can begin from a static page contract

Before introducing PHP or a database, the page structure can establish where navigation, categories, products, pagination, and footer content belong.

### 2. Bootstrap provides a reusable responsive grid

The catalog uses Bootstrap columns to separate the category sidebar from the product area and `col-md-4` product columns to obtain three cards per row at the `md` breakpoint and above.

### 3. Repeated static markup exposes the next refactoring opportunity

Twelve manually represented product cards make the repeated structure explicit. A later lesson can replace that repetition with data-driven rendering without changing the conceptual role of each card.

### 4. Static pagination is presentation, not pagination logic

Links for pages `1`, `2`, and `3` establish the visual component but do not yet select records or calculate offsets.

### 5. Absence of dynamic code is part of the evidence

The recovered artifact contains no PHP or database logic. Adding those concepts to this reproduction would blur the boundary between PHP 2 and the later database-backed lessons.

### 6. Reconstruction remains bounded by the available artifact

The available file supports a precise reconstruction of the static catalog. It cannot establish unrecorded classroom explanations or activities.

### 7. Reproduction requires execution and verification

The locally created catalog was not considered complete merely because the HTML existed. It was served over HTTP and its expected structure was checked in the actual response.

## 6. Final lesson state

PHP 2 is reconstructed to the available artifact, independently reproduced, served, and verified locally.

`PHP_2_LOCAL_REPRODUCTION=PASS`
