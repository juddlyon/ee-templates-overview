ExpressionEngine Templates Overview
=====================

Outputs ExpressionEngine template settings in a table for quick debugging.

## Installation & usage
1. Copy _templates_overview_ to your _system/expressionengine/third_party_ directory
2. Insert the following tag into a template
```{exp:templates_overview}
3. The following should output in an HTML template:
* site name (MSM only)
* template ID
* group
* name
* edit date [2013-10-19 22:52:15]
* PHP [yes|no]
* parse stage [input|output]
* type [webpage|css|404|js|xmlfeed|static
* save as file [yes|no]
* cache [yes|no]
* HTTP auth [yes|no]

![Screnshot](http://d.pr/i/RsRQ)
