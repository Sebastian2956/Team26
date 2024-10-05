// TODO: this url will have to be updated
// testing locally: http://localhost/Team26/LAMPAPI
// production : http://team26cm.seb.christmas/Team26/LAMPAPI
const urlBase = 'http://localhost/Team26/LAMPAPI';
const extension = 'php';

var userId = 0;
let firstName = "";
let lastName = "";

function showContactDetails(contactId) {
    let userId = readCookie();  // Retrieve the userId from cookies

    let url = urlBase + "/selectContact.php";  // Endpoint for retrieving a specific contact

    // Ensure both contactId and userId are sent
    let tmp = { contactId: contactId, userId: userId };  
    let jsonPayload = JSON.stringify(tmp);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);  // Using POST
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    xhr.onload = function() {
        console.log("Response: ", xhr.responseText);  // Log the raw response

        if (xhr.status === 200) {
            try {
                let response = JSON.parse(xhr.responseText);
                console.log("Parsed Response: ", response);  // Log the parsed response

                if (!response.error) {
                    // Retrieve contact-details and populate them
                    let contactDetails = document.getElementById("contact-details");
                    let button = document.getElementById("editButton");

                    button.textContent = "Edit";
                    button.style.backgroundColor = "white";

                    // Clear previous content
                    contactDetails.innerHTML = '';

                    // Create elements for each field using the data fetched from the server
                    let firstNameDetail = document.createElement("p");
                    firstNameDetail.innerHTML = `<strong>First Name:</strong> ${response.FirstName}`;

                    let lastNameDetail = document.createElement("p");
                    lastNameDetail.innerHTML = `<strong>Last Name:</strong> ${response.LastName}`;

                    let emailDetail = document.createElement("p");
                    emailDetail.innerHTML = `<strong>Email:</strong> ${response.Email}`;

                    let phoneDetail = document.createElement("p");
                    phoneDetail.innerHTML = `<strong>Phone:</strong> ${response.Phone}`;

                    // Append elements to contactDetails
                    contactDetails.appendChild(firstNameDetail);
                    contactDetails.appendChild(lastNameDetail);
                    contactDetails.appendChild(emailDetail);
                    contactDetails.appendChild(phoneDetail);

                    // Store the latest contact details in the dataset for future use
                    contactDetails.dataset.contact = JSON.stringify({
                        FirstName: response.FirstName,
                        LastName: response.LastName,
                        Email: response.Email,
                        Phone: response.Phone
                    });
                } else {
                    console.error("Error fetching contact details: " + response.error);
                }
            } catch (e) {
                console.error("JSON parse error: ", e);  // Log JSON parse errors
            }
        } else {
            console.error("Request failed. Status: " + xhr.status);
        }
    };

    xhr.send(jsonPayload);
}


function doPopulate() {
    readCookie();  // Ensure userId is retrieved from cookies

    let tmp = { userId: userId };
    let jsonPayload = JSON.stringify(tmp);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", urlBase + "/PopulateContactList.php", true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    xhr.onload = function() {
        if (xhr.status === 200) {
            let contacts = JSON.parse(xhr.responseText);
            let contactList = document.getElementById("contact-list");
            contactList.innerHTML = '';  // Clear existing contacts

            contacts.forEach(contact => {
                let li = document.createElement("li");
                li.setAttribute("data-first-name", contact.FirstName);
                li.setAttribute("data-last-name", contact.LastName);
                li.textContent = `${contact.FirstName} ${contact.LastName}`;  // Display contact name

                // Add an onclick event to load specific contact details
                li.onclick = function() {
                    showContactDetails(contact.ID);  // Pass contact.ID to fetch details
                };

                contactList.appendChild(li);  // Add contact to the list
            });
        } else {
            console.error('Request failed. Status: ' + xhr.status);
        }
    };

    xhr.send(jsonPayload);
}

function doSearch() {
    // Retrieve the search input value
    let searchElement = document.getElementById("search-input").value.toLowerCase();

    // Get all the contacts currently in the list
    let contacts = document.querySelectorAll('#contact-list li');
    
    // Loop through the contacts and filter based on first or last name
    contacts.forEach(contact => {
        let firstName = contact.getAttribute('data-first-name').toLowerCase();
        let lastName = contact.getAttribute('data-last-name').toLowerCase();

        // Show contact if the search input matches either first or last name
        if (firstName.startsWith(searchElement) || lastName.startsWith(searchElement)) {
            contact.style.display = ""; // Show matching contact
        } else {
            contact.style.display = "none"; // Hide non-matching contact
        }
    });
}

function filterContacts() {
    // Get the search input value and trim any extra spaces
    let input = document.getElementById("search-input").value.toLowerCase().trim();
    
    // Get all contacts in the contact list
    let contacts = document.querySelectorAll('#contact-list li');
    
    // Loop through all contacts and hide those that don't match the search query
    contacts.forEach(contact => {
        // Get the first and last names from the data attributes
        let firstName = contact.getAttribute('data-first-name').toLowerCase().trim();
        let lastName = contact.getAttribute('data-last-name').toLowerCase().trim();

        // Check if the input matches the start of either the first or last name
        if (firstName.startsWith(input) || lastName.startsWith(input)) {
            contact.style.display = ""; // Show matching contact
        } else {
            contact.style.display = "none"; // Hide non-matching contact
        }
    });
}

function doLogin()
{
	userId = 0;
	firstName = "";
	lastName = "";

  //gets the username and password from the input tag on the .html that calls it
	let login = document.getElementById("loginUsername").value;
	let password = document.getElementById("loginPassword").value;
//	var hash = md5( password );

	document.getElementById("loginResult").innerHTML = "";

  //creates javascript struct
  //this is a struct
	let tmp = {login:login,password:password};
  //	var tmp = {login:login,password:hash};
  // converts the struct to a json blob
	let jsonPayload = JSON.stringify( tmp );

  //this picks the php file
	let url = urlBase + '/Login.' + extension;

  //this is what actually creates the post request (AJAX)
	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
    //this defines the reponse processing functions
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{

				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( userId < 1 )
				{
					document.getElementById("loginResult").innerHTML = "<span>User/Password combination incorrect</span>";
					return;
				}

				saveCookie();

				window.location.href = "dashboard.html";
			}
		};
    //This sends the message to the url (Login.php)
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}


    // TODO: Testing
    // window.userId = 255;
}

// work in progress
function doRegister()
{
	console.log("doRegister working");
	let firstName = document.getElementById("registerFirstName").value;
	let lastName = document.getElementById("registerLastName").value;
	let login = document.getElementById("registerUsername").value;
	let password = document.getElementById("registerPassword").value;
//	var hash = md5( password );

	document.getElementById("registerResult").innerHTML = "";

	let tmp = {FirstName:firstName,LastName:lastName,login:login,password:password};

//	var tmp = {login:login,password:hash};
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddUser.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	try
	{
		xhr.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200)
			{
				let jsonObject = JSON.parse( xhr.responseText );
				userId = jsonObject.id;

				if( jsonObject.error !== "" )
				{
					document.getElementById("registerResult").innerHTML = jsonObject.error;
					return;
				}

				window.alert("Successful Registeration");

				saveCookie();

				window.location.href = "index.html";
			}
		};
		xhr.send(jsonPayload);
	}
	catch(err)
	{
		document.getElementById("registerResult").innerHTML = err.message;
		window.alert(err.message);
	}

}

function saveCookie()
{
	let minutes = 20;
	let date = new Date();
	date.setTime(date.getTime()+(minutes*60*1000));
	document.cookie = "firstName=" + firstName + ",lastName=" + lastName + ",userId=" + userId + ";expires=" + date.toGMTString();
}

function readCookie()
{
	userId = -1;
	let data = document.cookie;
	console.log("Cookies:", data);

	let splits = data.split(",");
	for(var i = 0; i < splits.length; i++)
	{
	    let thisOne = splits[i].trim();
		let tokens = thisOne.split("=");
		if( tokens[0] == "firstName" )
		{
			firstName = tokens[1];
		}
		else if( tokens[0] == "lastName" )
		{
			lastName = tokens[1];
		}
		else if( tokens[0] == "userId" )
		{
			userId = parseInt( tokens[1].trim() );
		}
	}

	if( userId < 0 )
	{
		window.location.href = "index.html";
	}
	else
	{
	console.log("User ID found:", userId);
	return userId;
}

}

function doLogout()
{
	userId = 0;
	firstName = "";
	lastName = "";
	document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
	window.location.href = "index.html";
}

function doDelete()
{
	let userId = readCookie();
	
	// Step 1: Get the element by ID
	let contactDetailsElement = document.getElementById("contact-details");

	// Step 2: Retrieve the data-contact attribute (it will be a string)
	let contactDataString = contactDetailsElement.getAttribute("data-contact");

	// Step 3: Parse the string as JSON
	let contactData = JSON.parse(contactDataString);

	// Step 4: Access the first name
	let firstName = contactData.FirstName;
	let lastName = contactData.LastName;
	
	let tmp = {userId: userId,contactFirstName:firstName,contactLastName:lastName};
	let jsonPayload = JSON.stringify(tmp);
	let xhr = new XMLHttpRequest();

	//finds the php file to run
	let url = urlBase + '/DeleteContact.' + extension;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

	// Handle the server's response
    xhr.onload = function() {
        if (xhr.status === 200) {
            
      	let response = JSON.parse(xhr.responseText);
            console.log("response: ", response);

            if (response.error === "") {
                console.log("Contact deleted successfully.");
            } else {
                console.error("Error deleting contact: " + response.error);
				window.alert("Contact Deleted");
            }
        } else {
            console.error("Request failed. Status: " + xhr.status);
        }
    };

	xhr.send(jsonPayload);
}

// not working; still need to read in userId from cookie
function doAddContact()
{
	console.log("doAddContact() working")

	let userId = readCookie();
	console.log("userId from cookie:", userId);
	
	let firstName = document.getElementById("contactFirstName").value;
	let lastName = document.getElementById("contactLastName").value;
	let email = document.getElementById("contactEmail").value;
	let phone = document.getElementById("contactPhone").value;

	document.getElementById("addResult").innerHTML = "";

	let tmp = {FirstName:firstName,LastName:lastName,Email:email,Phone:phone,userId:userId};
	console.log("Payload: ", tmp); 
	let jsonPayload = JSON.stringify( tmp );

	let url = urlBase + '/AddContact.' + extension;

	let xhr = new XMLHttpRequest();
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
	
	xhr.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			let jsonObject = JSON.parse( xhr.responseText );
			console.log(jsonObject);

			if( jsonObject.error !== "" ) {
				return;
			}

			document.getElementById("addResult").innerHTML = "Contact Added!";

		} else {
            console.error('Request failed. Status: ' + xhr.status);
			document.getElementById("addResult").innerHTML = xhr.status;

		}
	};
	xhr.send(jsonPayload);
}

function doEdit() {
    let contactDetails = document.getElementById("contact-details");

    // Parse current contact details
    let contact = JSON.parse(contactDetails.dataset.contact);
    let oldFirstName = contact.FirstName;
    let oldLastName = contact.LastName;

    let userId = readCookie();
    let newFirstName = document.getElementById("editFirstName").value;
    let newLastName = document.getElementById("editLastName").value;
    let newEmail = document.getElementById("editEmail").value;
    let newPhone = document.getElementById("editPhone").value;

    console.log(newFirstName, newLastName, newEmail, newPhone);
    
    let url = urlBase + "/UpdateContact.php";  // Ensure URL is correct
    
    let tmp = { 
        userId: userId, 
        FirstName: newFirstName, 
        LastName: newLastName, 
        Email: newEmail, 
        Phone: newPhone, 
        oldFirstName: oldFirstName, 
        oldLastName: oldLastName 
    };
    let jsonPayload = JSON.stringify(tmp);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");

    xhr.onload = function() {
        console.log("Server response: ", xhr.responseText); // Check the server response
        if (xhr.status === 200) {
            let response = JSON.parse(xhr.responseText);

            // Handle success when error field is empty or undefined
            if (!response.error) {
                console.log("Update successful");

                // Update the contact list with new details
                let contactElement = document.querySelector(`#contact-list li[data-first-name="${oldFirstName}"][data-last-name="${oldLastName}"]`);
                if (contactElement) {
                    contactElement.textContent = `${newFirstName} ${newLastName}`;
                    contactElement.setAttribute('data-first-name', newFirstName);
                    contactElement.setAttribute('data-last-name', newLastName);
                }

                // Reflect updated details in the contact details section
                contactDetails.innerHTML = `
                    <p><strong>First Name:</strong> ${newFirstName}</p>
                    <p><strong>Last Name:</strong> ${newLastName}</p>
                    <p><strong>Email:</strong> ${newEmail}</p>
                    <p><strong>Phone:</strong> ${newPhone}</p>
                `;

                // Update the contact object in the dataset
                contactDetails.dataset.contact = JSON.stringify({
                    FirstName: newFirstName,
                    LastName: newLastName,
                    Email: newEmail,
                    Phone: newPhone
                });

                // Switch the button text back to "Edit"
                const button = document.getElementById("editButton");
                const cancelButton = document.getElementById("cancelButton");
                button.textContent = "Edit";
                button.style.backgroundColor = "white";
                cancelButton.style.display = "none"; // Hide cancel button
            } else {
                console.error("Edit unsuccessful: " + response.error);
            }
        } else {
            console.error("Request failed. Status: " + xhr.status);
        }
    };

    xhr.send(jsonPayload);
}

function toggleEdit() {
    // Retrieve contact-details
    let contactDetails = document.getElementById("contact-details");
    const button = document.getElementById("editButton");
    const cancelButton = document.getElementById("cancelButton");

    if (button.textContent === "Save") {
        // Confirmation prompt before saving
        if (confirm("Are you sure you want to save the changes?")) {
            // Save the edited details
            doEdit();  // Call doEdit() directly, no need for a callback

            // Switch back to "Edit" mode after saving
            button.textContent = "Edit";
            button.style.backgroundColor = "white";
            cancelButton.style.display = "none"; // Hide the cancel button after saving

            // Switch input fields back to text
            let contact = JSON.parse(contactDetails.dataset.contact);
            contactDetails.innerHTML = `
                <p><strong>First Name:</strong> <span id="contact-first-name">${contact.FirstName}</span></p>
                <p><strong>Last Name:</strong> <span id="contact-last-name">${contact.LastName}</span></p>
                <p><strong>Email:</strong> <span id="contact-email">${contact.Email}</span></p>
                <p><strong>Phone:</strong> <span id="contact-phone">${contact.Phone}</span></p>
            `;
        }
    } else {
        // Enter "Edit" mode, allow editing of the contact details

        // Parse contact-details data
        let contact = JSON.parse(contactDetails.dataset.contact);
        
        // Store the original contact details in case the user cancels
        contactDetails.dataset.original = JSON.stringify(contact);

        // Clear previous content
        contactDetails.innerHTML = '';

        // Create elements for each field (First Name, Last Name, Email, Phone)
        
        // First Name
        let firstNameDetail = document.createElement("p");
        firstNameDetail.innerHTML = '<strong>First Name:</strong> ';
        let firstNameInput = document.createElement("input");
        firstNameInput.type = "text";
        firstNameInput.value = contact.FirstName;
        firstNameInput.id = "editFirstName";
        firstNameDetail.appendChild(firstNameInput);

        // Last Name
        let lastNameDetail = document.createElement("p");
        lastNameDetail.innerHTML = `<strong>Last Name:</strong> `;
        let lastNameInput = document.createElement("input");
        lastNameInput.type = "text";
        lastNameInput.value = contact.LastName;
        lastNameInput.id = "editLastName";
        lastNameDetail.appendChild(lastNameInput);

        // Email
        let emailDetail = document.createElement("p");
        emailDetail.innerHTML = `<strong>Email:</strong> `;
        let emailInput = document.createElement("input");
        emailInput.type = "email";
        emailInput.value = contact.Email;
        emailInput.id = "editEmail";
        emailDetail.appendChild(emailInput);

        // Phone
        let phoneDetail = document.createElement("p");
        phoneDetail.innerHTML = `<strong>Phone:</strong> `;
        let phoneInput = document.createElement("input");
        phoneInput.type = "text";
        phoneInput.value = contact.Phone;
        phoneInput.id = "editPhone";
        phoneDetail.appendChild(phoneInput);

        // Append the new input fields to the contact details section
        contactDetails.appendChild(firstNameDetail);
        contactDetails.appendChild(lastNameDetail);
        contactDetails.appendChild(emailDetail);
        contactDetails.appendChild(phoneDetail);

        // Switch to "Save" mode, allow the user to edit details
        button.textContent = "Save";
        button.style.backgroundColor = "white";
        cancelButton.style.display = "inline";  // Show the cancel button
    }
}

function cancelEdit() {
    let contactDetails = document.getElementById("contact-details");
    const button = document.getElementById("editButton");
    const cancelButton = document.getElementById("cancelButton");

    // Retrieve the original contact details from the stored dataset
    let originalContact = JSON.parse(contactDetails.dataset.original);

    // Switch fields back to non-editable text
    contactDetails.innerHTML = `
        <p><strong>First Name:</strong> ${originalContact.FirstName}</p>
        <p><strong>Last Name:</strong> ${originalContact.LastName}</p>
        <p><strong>Email:</strong> ${originalContact.Email}</p>
        <p><strong>Phone:</strong> ${originalContact.Phone}</p>
    `;

    // Switch the button text back to "Edit"
    button.textContent = "Edit";
    button.style.backgroundColor = "white";

    // Hide the cancel button
    cancelButton.style.display = "none";
}

// Automatically populate contacts when the dashboard page loads
window.onload = function() {
    // Only run doPopulate() if the current page is dashboard.html
    if (window.location.pathname.includes("dashboard.html")) {
        doPopulate(); // Automatically populate contacts on page load
    }
};
