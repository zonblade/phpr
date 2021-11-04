this is new repository for php-framework

# Introduction

PHPR or PHP Array Framework is a framework highly dependent to an array structure. PHPR Framework uses URL Dispatcher type which looks like [Django](https://docs.djangoproject.com/en/3.2/topics/http/urls/) dispatcher. And PHPR bringing Modular type of framework into PHP framework world. Now, you dont need to set up everything from the start. Someone can give you only the apps and you're ready to go. Or, multiple development which each person working on 1 apps and compile into one.

- [x] Project Initiator : [zonblade](https://instagram.com/zonblade)
- [x] System Inspiration : [Django](https://docs.djangoproject.com/)

```
currently under intense development,
not recomended to use current version.
```

## Basic Folder Structure
```bash
├── assets
├── module
│   ├── .system
│   ├── apps
│   │   ├── your_apps_1
│   │   │   ├── subapps
│   │   │   │   ├── view.php
│   │   │   ├── urls.php
│   │   ├── your_apps_2
│   │   │   ├── subapps
│   │   │   │   ├── view.php
│   │   │   ├── urls.php
│   ├── root.php
│   ├── manage.php
│   ├── urls.php
│   └── settings.env
├── static
├── index.php
└── .htaccess (auto generated!)
```
