# Team26
Team 26 Contact Manager

team26cm.seb.christmas

#List of changes:

- Changed the color scheme of the dashboard
- Moved the search bar and button
- Fixed the issue with contacts not loading on login
 - previously had to press the search button for them to show
- Changed the search bar to have Predictive search functionality
- Fixed issue where results were only found if there was a full exact match
 - the only way to return results was to match the name in its entirety
- Search bar now returns in real-time matches of any pattern, can be first or last name patterns
- Search button is just now a button for accessibility but uses the search bar functionality
- Removed the save button and combined it with the edit button
- Updates to contacts now properly update contact details and contact list in real-time
 - no longer requires a page reload
- Added a confirm prompt when save button is pressed
- Added a cancel button during the edit functionality that allows user to cancel all changes to that contact
 - only visible when editing a contact
- Changed the save button from green to white
- Added a selectContact.php file to streamline real-time updates of contact list after updating a Contact
 - There was a bug that when you edited a contact then swapped to another contact and returned to the previous contact that was just edited the contact details of the selected contact were incorrect

#To Do:

- Up long in right panel has a welcome display until a contact is selected
- Fix the delete and add buttons to behave similar to the edit button

#Possible Additions (Time Permitting)

- Add a placeholder for photo
- Possibly add more fields for contacts details
 - non-interactive/fucntional purely to fill white space
- Make sure the list is alphabetical

# Collaborators
- Sebastian Llano (PM)
- Liam Harvell (API 1)
- Owen Burow (API 2)
- Devak Sharma (Frontend 1)
- Jose Kostyun (Frontend 2)
- Zachary Mohamed (DBA)

