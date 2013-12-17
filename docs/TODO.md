- Check if product name is not taken by another product while updating data
- Sort available suppliers by their name in add/edit product form
- Add recalculating product brutto price in JavaScript
- Implement handling clicking on cancel button in the forms
- Cut too long strings, becasue they cause exception while saving that data in database
- Move interfaces files to separate folders
- Do not create Form object in the controllers, but use appropriate methods
- Add translation of the messages after (un)successful adding new object
- Implement deleting objects of all entities
 + Integrate SoftDelete behaviour (from Gedmo extensions to Doctrine 2 ORM) with all database entities
- Integrate Identification Numbers filters in Company form
 + VAT Identification Number (e.g. NIP in Poland)
   e.g. 526-030-07-24 => 5260300724
 + Business Entity Identification Number (e.g. KRS in Poland)
   e.g. 10069 => 0000010069
- Integrate Identification Numbers validators in Company form
 + VAT Identification Number (e.g. NIP in Poland)
 + Enterprise Identification Number (e.g. REGON in Poland)
 + Business Entity Identification Number (e.g. KRS in Poland)
- Add unit tests
- Integrate Timestable behaviour (from Gedmo extensions to Doctrine 2 ORM) with all database entities
- Add Zend\Cache to speed up application
+ Add additional indexes in the database e.g. for product name
