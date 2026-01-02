# Lapis Orkestra – Vendor Blog Module

## Overview

The **Vendor Blog Module** is an optional feature module designed for the **Lapis Orkestra** backend framework. It provides basic blog-related functionality (such as posts, categories, and related application structure) and serves primarily as a **research and demonstration module** for evaluating modularity, extensibility, and incremental development within the Lapis Orkestra architecture.

This module is developed as part of an academic research project and is **not intended for production use**.

---

## Purpose in the Thesis Context

This module exists to support the Master’s thesis titled:

> *Maintenance and Incremental Development of Software, with a Specific Focus on Modularity*

Specifically, the Vendor Blog Module is used to:

* Demonstrate **feature-oriented modular design**
* Evaluate how new features can be added without modifying the core framework
* Support **change scenario–based evaluation** in the thesis methodology
* Illustrate separation between the core framework and external vendor modules

The module is intentionally minimal and focuses on **architectural structure**, not completeness.

---

## Project Status

* Research prototype
* Feature-incomplete by design
* No production hardening
* No automated test coverage

This module should be treated as an **experimental artifact** rather than a reusable production package.

---

## Requirements

* PHP ^8.1
* Lapis Orkestra (research prototype)

The module depends on the Lapis Orkestra framework being available as a Composer dependency.

---

## Installation (Prototype Setup)

Because the framework and modules are not yet published on Packagist, installation is done via Git repositories.

### Step 1: Add repository to your project

```json
"repositories": [
  {
    "type": "vcs",
    "url": "https://github.com/HanafiAhmat/lapis-orkestra"
  },
  {
    "type": "vcs",
    "url": "https://github.com/HanafiAhmat/lapis-orkestra-vendor-blog-module"
  }
]
```

### Step 2: Require the module

```bash
composer require hanafiahmat/lapis-orkestra-vendor-blog-module:dev-main
```

---

## Module Registration

The module declares its metadata via `composer.json` under the `extra.lapis-module` key:

```json
"extra": {
  "lapis-module": {
    "module-key": "Blog",
    "module-path": "src",
    "priority": 150
  }
}
```

This allows the Lapis Orkestra framework to:

* Discover the module automatically
* Register it during the application bootstrap process
* Control loading order using module priority

---

## Architecture Notes

* The module follows the same **layered and modular conventions** as the core framework
* Business logic, routing, and configuration are encapsulated within the module boundary
* No direct modification of the framework core is required

This design supports the thesis claim that **new features can be added with localized changes**.

---

## Relationship to Lapis Orkestra

This module is designed to:

* Depend on the framework
* Remain decoupled from other modules
* Be removable without affecting unrelated features

It demonstrates how **vendor modules** can extend the framework without increasing coupling in the core system.

---

## License

This project is licensed under the **MIT License**.

---

## Author

**Hanafi Ahmat**
Master’s Candidate – Computer Science
GitHub: [https://github.com/HanafiAhmat](https://github.com/HanafiAhmat)

---

## Disclaimer

This module is provided **for academic research and demonstration purposes only**. It is not production-ready and should not be used in live systems.
