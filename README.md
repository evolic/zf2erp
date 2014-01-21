Unity ZF2 tutorial application
--------------------------------
Sample Zend Framework 2 application, which could be a core for ERP system


About the project
--------------------------------
It is sample ZF2 project, which was created according to specification of the application
from the Techcamp October 2013's contest.

Specification (in Polish) is available at:
http://zf2erp.tomaszkuter.com/docs/Zadanie-programistyczne2.docx


Technology
--------------------------------
In the project there were used:

* PostgreSQL,
* Zend Framework 2.2,
* Doctrine 2 ORM,
* Gedmo - Doctrine 2 ORM Extension,
* jQuery 1.9.x,
* jQuery DataTables 1.9.4,
* Bootstrap 3.0.3,
* Bootstrap TouchSpin 2.3.0


Documentation page:
--------------------------------
http://zf2erp.tomaszkuter.com/en-US/docs


TODO list
--------------------------------

* Check if product name is not taken by another product while updating data
* Sort available suppliers by their name in add/edit product form
* ~~Add recalculating product brutto price in JavaScript~~ [2014-01-20]
* Implement handling clicking on cancel button in the forms
* Cut too long strings, becasue they cause exception while saving that data in database (input filter)
* Move interfaces files to separate folders
* Do not create Form object in the controllers, but use appropriate methods
* Add translation of the messages after (un)successful adding new object
* Implement deleting objects of all entities
  * Integrate SoftDelete behaviour (from Gedmo extensions to Doctrine 2 ORM) with all database entities
* Integrate Identification Numbers filters in Company form<br>
  * VAT Identification Number (e.g. NIP in Poland)<br>
    e.g. 526-030-07-24 => 5260300724
  * Business Entity Identification Number (e.g. KRS in Poland)<br>
    e.g. 10069 => 0000010069
* Integrate Identification Numbers validators in Company form<br>
  * VAT Identification Number (e.g. NIP in Poland)
  * Enterprise Identification Number (e.g. REGON in Poland)
  * Business Entity Identification Number (e.g. KRS in Poland)
* Add unit tests
* Integrate Timestable behaviour (from Gedmo extensions to Doctrine 2 ORM) with all database entities
* Add Zend\Cache to speed up application
* Add additional indexes in the database e.g. for product name
* Remove adding multiple forms to the controllers. Use setting form properties inside getForm() method.

See the application live at http://zf2erp.tomaszkuter.com/
