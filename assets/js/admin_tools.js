window.addEventListener('load', function()
{	
	// -- CREATE DOM ELEMENTS --
	let createDomElem = function (type, attributes)
    {
    	let newElem = document.createElement(type);
    	for (let i = attributes[0].length - 1; i >= 0; i--)
    	{
    		newElem.setAttribute(attributes[0][i], attributes[1][i]);
    	}
    	return newElem;
    }


    let loadModal = function(type)
    {
        let modalContainer = createDomElem("div", [["class"], ["modalContainer"]]);
        let modalBox = createDomElem("div", [["id", "class"], ["modalBox", "modalBox"]]);
        let modalContent = createDomElem("div", [["class"], ["modalContent"]]);
        let leaveModalButton = createDomElem("p", [["class"], ["leaveModalButton"]]);
        let postButton;

        if (type == "classic")
        {
            leaveModalButton.innerText = "X";
            postButton = createDomElem("button", [["id", "class"], ["modalPost", "formButton"]]);
            postButton.innerHTML = "Valider";

            modalBox.appendChild(leaveModalButton);
            modalBox.appendChild(modalContent);
            modalBox.appendChild(postButton);

            leaveModalButton.onclick = function()
            {
                modalContainer.remove();
            }
        }
        // alert!!
        else
        {
            modalBox.className = "closedQuestionAlert";

            postYesButton = createDomElem("button", [["id", "class"], ["modalPostYes", "formButton postYesButton"]]);
            postYesButton.innerHTML = "Oui";
            postNoButton = createDomElem("button", [["id", "class"], ["postNoButton", "formButton"]]);
            postNoButton.innerHTML = "Non";
            modalBox.appendChild(modalContent);
            modalBox.appendChild(postYesButton);
            modalBox.appendChild(postNoButton);

            postNoButton.onclick = function()
            {
                modalContainer.remove();
            }
        }

        modalContainer.appendChild(modalBox);
        document.body.appendChild(modalContainer);

        return modalContent;
    }

    if (document.querySelector('#button_create'))
    {
    	// -- VALIDATION WINDOW --
    	let validate = function(form, action, text)
    	{
    		// Create Modal
            textContainer = loadModal("closedQuestionAlert");
    		textContainer.innerHTML = text;

    		let validateYes = function()
    		{
    			modalContainer.remove();
        		form.action = 'admin.php?action='+action;
        		form.submit();
        	}

    		document.getElementById("modalPostYes").addEventListener('click', validateYes, false);
    	}
    	// -- CLOSE TOOLS --
    	let closeTools = function(close)
    	{
    		let lastClassroomContainerResult;
    		if (close == "create")
    		{
    			// Hide Create Form
    			if (!createForm.classList.contains('hide'))
    			{
    			    createForm.classList.toggle("hide");
    			    createButton.classList.toggle("toolFocus");	
    			}
    		}
    		if (close == "rename" || close == "create")
    		{
            	// Remove Rename Field if Already Exist
            	if (document.querySelector('.listElementRenameContainer'))
            	{
            		let lastElementRename = document.querySelector('.listElementRenameContainer');
            		lastElementParentRename = lastElementRename.parentElement;
            		lastElementParentRename.removeChild(lastElementRename);
            		lastClassroomContainerResult = lastElementParentRename;
            	}
            	// Remove Submit if Already Exist
            	if (document.querySelector('#submit'))
            	{
            		let lastSubmit = document.querySelector('#submit');
            		let lastSubmitParent = lastSubmit.parentElement;
            		lastSubmitParent.removeChild(lastSubmit);
            	}
            	// Display Static Name
            	let elementNames = document.querySelectorAll('.list .listElementName');
            	for (let i = elementNames.length - 1; i >= 0; i--)
            	{
            		if (elementNames[i].classList.contains('hide'))
            		{
            			elementNames[i].classList.remove('hide');
            			elementNames[i].classList.add('listElementName');
            		}
            	}
            }
            return lastClassroomContainerResult;
    	}
        // -- DELETE SCRIPT --
    	let button_deleteClassrooms = document.querySelector('#delete');
    	let confirmDeleteSelectedClassrooms = function()
    	{
    		closeTools("create");
            let selectedClassrooms = document.querySelector('.list');
            let listElementsContainer = document.querySelectorAll('.listElementDeleteCheck');
            for (let i = listElementsContainer.length - 1; i >= 0; i--)
            {
            	if (listElementsContainer[i].checked == true)
            	{
            		if (detectDeleteElement == "students")
            		{
            			validate(selectedClassrooms, 'deleteStudents&idcr='+deleteElementPartofLink, '<p class="smsAlert"> ATTENTION! Cette opération est irréversible! Êtes-vous sûr de vouloir effacer le(s) élèves(s) sélectionné(s)?</p>');
            		}
            		else
            		{
            			validate(selectedClassrooms, 'deleteClassrooms', '<p class="smsAlert"> ATTENTION! Cette opération est irréversible! Tous les élèves appartenant à la classe seront eux aussi effacés! Êtes-vous sûr de vouloir effacer la/les classe(s) sélectionnée(s)?</p>');
            		}
       				return;
            	}
            }
            let smsContainer = document.querySelector('.sms');
            smsContainer.innerHTML = '<span class="smsAlert">Vous n\'avez rien Sélectionné!</span>';
    	}
        button_deleteClassrooms.addEventListener('click', confirmDeleteSelectedClassrooms, false);

    	// -- CREATE SCRIPT --
        let createButton = document.querySelector('#button_create');
        let createForm = document.querySelector('.form_create');
        let openCreateTool = function()
        {            	
        	closeTools("rename");
            createForm.classList.toggle("hide");
            createButton.classList.toggle("toolFocus");
            // autofocus
            if (createForm.classList != "hide")
            {
                let inputNewStudentNickname = document.querySelector('.newStudentNick');
                inputNewStudentNickname.focus();
            }
        }

        createButton.addEventListener('click', openCreateTool, false);

        // -- RENAME SCRIPT --
        let button_rename = document.querySelectorAll('.buttonRename');
        let openRenameTool = function(event)
        {
        	let lastClassroomContainer = closeTools("create");
        	// Create Rename Field
        	let buttonRename = event.target;
        	let classroomContainer = buttonRename.parentElement;
        	if (lastClassroomContainer != classroomContainer)
        	{
            	let classroomName = classroomContainer.querySelector('.listElementName').innerText;
            	let classroomId = classroomContainer.querySelector(".listElementDeleteCheck").value;
            		// Name Field
            	// Name Field
    			let listElementRenameContainer = createDomElem("div", [["class"], ["listElementRenameContainer"]]);
        		let listElementRename = createDomElem("input", [["type", "name", "value", "class"], ["text", "rename", classroomName, "listElementRename"]]);
    			listElementRenameContainer.appendChild(listElementRename);
    			if (detectDeleteElement == "students")
    			{
    				// Password Field
    				let password = classroomContainer.querySelector('.pwd').innerText;
        			let listElementPassword = createDomElem("input", [["type", "name", "value", "class"], ["text", "newPassword", password, "listElementRename"]]);
    				listElementRenameContainer.appendChild(listElementPassword);
    			}
            		// classroomId Field
        		let listElementId = createDomElem("input", [["type", "name", "value"], ["hidden", "idElem", classroomId]]);
    			listElementRenameContainer.appendChild(listElementId);
    			classroomContainer.insertBefore(listElementRenameContainer, buttonRename);
    				// submit
        		let submit = createDomElem("input", [["type", "value", "id", "class"], ["submit", "Enregistrer", "submit", "formButton"]]);
    			classroomContainer.appendChild(submit);

    			let elementName = classroomContainer.querySelector('.listElementName');
    			elementName.classList.toggle("hide");
    			document.querySelector('.listElementRename').focus();
    		}
        }

        for (let i = button_rename.length - 1; i >= 0; i--)
        {
            button_rename[i].addEventListener('click', openRenameTool, false);
        }
    }

    // -- PROFIL INFOS --
    if (document.getElementById("changeMail"))
    {
        let changeMail = document.getElementById("changeMail");
        let changePassword = document.getElementById("changePassword");
        let deleteAccount = document.getElementById("deleteAccount");

        let launchChangePasswordManagement = function()
        {
            let modalContent = loadModal("classic");
            modalContent.innerHTML += "<p>Mot de Passe Actuel<input type='password' name='oldPwd'></p>";
            modalContent.innerHTML += "<p>Nouveau Mot de Passe<input type='password' name='newPwd'></p>";
            modalContent.innerHTML += "<p>Répeter le Nouveau Mot de Passe<input type='password' name='newPwd2'></p>";
        }

        let launchdeleteAccountManagement = function()
        {
            let modalContent = loadModal("closedQuestionAlert");
            modalContent.innerHTML += "<p class='smsAlert'>Cette action supprimera votre compte ainsi que celui de tous vos élèves et ce de façon irréversible!</p>";
        }

        changePassword.addEventListener('click', launchChangePasswordManagement, false);
        deleteAccount.addEventListener('click', launchdeleteAccountManagement, false);
    }
}, false);