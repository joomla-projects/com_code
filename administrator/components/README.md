#Convert com_code to archiving system
com_code is the component running on http://developer.joomla.org that extracts issue tracker data from JoomlaCode and stores it within the site.

In earlier versions, the component featured a full front-end display that showed recent issues, nightly build reports, and an overall project summary.

Until discontinuing its use as our issue tracker, the component’s use became only synchronizing the data and extracting activity data for the activity charts that used to be available on the developer site.  

The component is therefore usable as a static archive for the project’s issue data on JoomlaCode.

Based on its current condition, the following general updates are required for the component to make it usable as a static archive. (see https://github.com/mbabker/bug-squad-stuff/tree/archive)

## Routing
The existing routing code is based on internal aliases and IDs.  

If we are to try and redirect traffic from JoomlaCode to the archive, the internal routing variables will need to use the JoomlaCode IDs instead of our own database IDs or aliases.

The JoomlaCode ID's are all tracked in the database.

### Example Redirects Needed:
#### NOTE: 
com_code URLs shouldn’t be indexed right now as they aren’t hooked up to any menu items nor are any links to these pages published to the best of my knowledge

##### Trackers List View URL:
**Old:** http://joomlacode.org/gf/project/joomla/tracker/

**New**: http://developer.joomla.org/component/code/cms.html?view=trackers

##### 1.5 Issue Tracker
**Old:** http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemBrowse&tracker_id=32

**New:** http://developer.joomla.org/component/code/cms.html?view=tracker&tracker_id=1

The JoomlaCode tracker ID is stored as jc_tracker_id in our tables and tracker_id is the internal primary key for the issues table.

#####Single Issue Report

**Old:** http://joomlacode.org/gf/project/joomla/tracker/?action=TrackerItemEdit&tracker_item_id=16717

**New:** http://developer.joomla.org/component/code/cms.html?view=issue&tracker_alias=cms-1.5-issues&issue_id=4059

The JoomlaCode issue ID is stored as jc_issue_id in our tables, the tracker_item_id field is not used in our code.

## To Do List
### Data Storage

#### To-Do
- Remove archiving of activity related data (tables that were specifically used for the activity charts)

- Remove archiving of assignment data (this table indicates which users were assigned to which issues)

- Remove projects support (this is an internal feature to com_code)

#### Done
- Remove archiving of file related data

- Remove Joomla asset support from project and tracker tables

### Issue Item View
- Add commit reference data. 

  This data is already collected, but we need to map which issues were closed by a commit in old SVN.

  I'm not sure there will be anything for us to link this to, but at least listing the commit message gives someone a reference to search in git by
full display of relevant issue data. 

- Collect missing output for data (reference the links above to get an idea)
  * Submitter 
  * When the issue was created

### Issue List View
- Add the issue ID as a column

- Make the results sortable/searchable

- Fix Pagination. Right now it is FUBAR

- Priorities should probably be mapped from their number (1) to how they were described (High)

### Tracker List View
- Does this need any changes?

### Component Admin
Do we need to be able to manage anything from here?  

Main thing would be the trackers.  

Right now, tracker IDs are hardcoded into the sync scripts. 

We probably should change this before collecting the archive to use the trackers from the database and add basic admin UI to add/edit/delete/publish/unpublish these items.
