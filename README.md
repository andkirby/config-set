# config-set
Magento extension ConfigSet importing system configuration.

Version: 1.0.0

Please use path to find the module functionality:
```
Admin Panel > System > Configuration > Developer > Configuration Importing
```

### Import configuration file
You may import configuration with a CSV file.

Path in Systems Configuration:
```
Developer > Configuration Importing > Upload configuration CSV file
```

#### CSV file format
There is the simple format: `xpath,value`.

Example:
```
web/seo/use_rewrites, 1
web/cookie/cookie_lifetime, 360000
admin/security/use_form_key, 0
admin/security/session_cookie_lifetime, 900000
dev/debug/profiler, 1
```

**Warning:** Please be careful with uploading configuration file 
because you can damage your Magento instance with incorrect configuration.

### Show XPath of configuration nodes
You may find useful to show XPaths in configuration. It's possible by enabling option:
```
Developer > Configuration Importing > Show Configuration XPath 
```

**Note:** This option will be saved in admin session only because 
- it can overload configuration pages loading,
- other admin users.
