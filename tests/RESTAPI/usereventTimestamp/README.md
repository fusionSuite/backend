# User event and timestamp

These tests will test for:

* config/properties
* config/types
* items

The following:

* creating
  * fill *created_at* with datetime
  * fill *updated_at* with datetime
  * fill *created_by* with user id
* updating
  * update *updated_at* with datetime
  * fill *updated_by* with user id
* soft deleting
  * fill *deleted_at* with datetime
  * fill *deleted_by* with user id
* restoring from soft delete
  * fill *deleted_at* to null
  * fill *deleted_by* to null
  * update *updated_at* with datetime
  * update *updated_by* with user id

