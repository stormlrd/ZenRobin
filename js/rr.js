function check_award_form( awardform ) {
	var error_msg = "The following errors were found: \n";
	var error_code = 1;
	
	if (awardform.cname.value == "null") {
		error_msg = error_msg + "\t* No player name selected\n";
		error_code = 0;
	}
	if (awardform.lootname.value == "null") {
		error_msg = error_msg + "\t* Select a Loot Type\n";
		error_code = 0;
	}
	if (awardform.other_loot.style.display == "") {
		if (awardform.other_loot.value == "") {
			error_msg = error_msg + "\t* Enter an other Loot Name\n";
			error_code = 0;
		}
	}
	if ((!awardform.dkppaid.value)|| (!IsNumeric(awardform.dkppaid.value))) {
		error_msg = error_msg + "\t* Enter a real DKP value\n";
		error_code = 0;
	}
	if (error_code == 0) {
		alert(error_msg);
		return false;
	} else {
		return true;
	}
}

function IsNumeric( sText ) {
   var ValidChars = "0123456789.";
   var IsNumber=true;
   var Char;
   
   for (i = 0; i < sText.length && IsNumber == true; i++) { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) {
         IsNumber = false;
      }
   }
   return IsNumber;
}

function dkp_item_names( box ) {
// Function to handle select box population and name/slot values for adding to the database
// - Saves having to manually edit the entry after awarding loot.
		
	if (document.forms['award'].lootname) {
		if (document.forms['award'].lootname.value == "Other") {
			document.forms['award'].loottype.style.display = "none";
			document.forms['award'].other_loot.style.display = "";
			document.getElementById("no_rr_update").style.display = "none";
			document.getElementById("rr_update_toggle").disabled = true;
		}
		if ((document.forms['award'].lootname.value == "Tier 1") || (document.forms['award'].lootname.value == "Tier 2") || (document.forms['award'].lootname.value == "Epic Quest")
			 || (document.forms['award'].lootname.value == "null")) {
			document.forms['award'].loottype.style.display = "";
			document.forms['award'].other_loot.style.display = "none";
			if (document.forms['award'].lootname.value == "null") {
				document.getElementById("no_rr_update").style.display = "none";
				document.forms['award'].dkppaid.value = "";
			} else {
				document.getElementById("no_rr_update").style.display = "";
			}
			document.getElementById("rr_update_toggle").disabled = false;
		}

	var lists = new Array();
	
	lists['null']			= new Array();
	lists['null'][0]		= new Array(
				'Select a Loot Type'
	);
	lists['null'][1]		= new Array(
				'Select a Loot Type'
	);
	
	// Quick fix to avoid JavaScript error when selecting 'Other'
	lists['Other']			= new Array();
	lists['Other'][0]		= new Array(
				''
	);
	lists['Other'][1]		= new Array(
				''
	);	
	
	if (getQueryVariable("viewselect") == "Druid") {
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Cenarion Helm',
				'Cenarion Spaulders',
				'Cenarion Vestments',
				'Cenarion Bracers',
				'Cenarion Gloves',
				'Cenarion Belt',
				'Cenarion Leggings',
				'Cenarion Boots'
		);
		lists['Tier 1'][1]	= new Array(
				'Cenarion Helm-Head',
				'Cenarion Spaulders-Shoulders',
				'Cenarion Vestments-Chest',
				'Cenarion Bracers-Wrists',
				'Cenarion Gloves-Hands',
				'Cenarion Belt-Waist',
				'Cenarion Leggings-Legs',
				'Cenarion Boots-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Stormrage Cover',
				'Stormrage Pauldrons',
				'Stormrage Chestguard',
				'Stormrage Bracers',
				'Stormrage Handguards',
				'Stormrage Belt',
				'Stormrage Legguards',
				'Stormrage Boots'
		);
		lists['Tier 2'][1]	= new Array(
				'Stormrage Cover-Head',
				'Stormrage Pauldrons-Shoulders',
				'Stormrage Chestguard-Chest',
				'Stormrage Bracers-Wrists',
				'Stormrage Handguards-Hands',
				'Stormrage Belt-Waist',
				'Stormrage Legguards-Legs',
				'Stormrage Boots-Feet'
		); 
	} // end Druid loot list

	if (getQueryVariable("viewselect") == "Hunter") {
		
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(

				'Giantstalker\'s Helmet',
				'Giantstalker\'s Epaulets',
				'Giantstalker\'s Breastplate',
				'Giantstalker\'s Bracers',
				'Giantstalker\'s Gloves',
				'Giantstalker\'s Belt',
				'Giantstalker\'s Leggings',
				'Giantstalker\'s Boots'

		);
		lists['Tier 1'][1]	= new Array(
				'Giantstalker\'s Helmet-Head',
				'Giantstalker\'s Epaulets-Shoulders',
				'Giantstalker\'s Breastplate-Chest',
				'Giantstalker\'s Bracers-Wrists',
				'Giantstalker\'s Gloves-Hands',
				'Giantstalker\'s Belt-Waist',
				'Giantstalker\'s Leggings-Legs',
				'Giantstalker\'s Boots-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Dragonstalker\'s Helm',
				'Dragonstalker\'s Spaulders',
				'Dragonstalker\'s Breastplate',
				'Dragonstalker\'s Bracers',
				'Dragonstalker\'s Gauntlets',
				'Dragonstalker\'s Belt',
				'Dragonstalker\'s Legguards',
				'Dragonstalker\'s Greaves'
		);
		lists['Tier 2'][1]	= new Array(
				'Dragonstalker\'s Helm-Head',
				'Dragonstalker\'s Spaulders-Shoulders',
				'Dragonstalker\'s Breastplate-Chest',
				'Dragonstalker\'s Bracers-Wrist',
				'Dragonstalker\'s Gauntlets-Hands',
				'Dragonstalker\'s Belt-Waist',
				'Dragonstalker\'s Legguards-Legs',
				'Dragonstalker\'s Greaves-Feet'
		);
		// Epic quest items
		lists['Epic Quest']		= new Array();
		lists['Epic Quest'][0]	= new Array(
				'Ancient Petrified Leaf',
				'Mature Black Dragon Sinew'
		);
		lists['Epic Quest'][1]	= new Array(
				'Ancient Petrified Leaf-Epic Quest',
				'Mature Black Dragon Sinew-Epic Quest'
		);
	} // end Hunter loot list
	
	if (getQueryVariable("viewselect") == "Mage") {
		
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Arcanist Crown',
				'Arcanist Mantle',
				'Arcanist Robes',
				'Arcanist Bindings',
				'Arcanist Gloves',
				'Arcanist Belt',
				'Arcanist Leggings',
				'Arcanist Boots'
		);
		lists['Tier 1'][1]	= new Array(
				'Arcanist Crown-Head',
				'Arcanist Mantle-Shoulders',
				'Arcanist Robes-Chest',
				'Arcanist Bindings-Wrists',
				'Arcanist Gloves-Hands',
				'Arcanist Belt-Waist',
				'Arcanist Leggings-Legs',
				'Arcanist Boots-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Netherwind Crown',
				'Netherwind Mantle',
				'Netherwind Robes',
				'Netherwind Bindings',
				'Netherwind Gloves',
				'Netherwind Belt',
				'Netherwind Pants',
				'Netherwind Boots'
		);
		lists['Tier 2'][1]	= new Array(
				'Netherwind Crown-Head',
				'Netherwind Mantle-Shoulders',
				'Netherwind Robes-Chest',
				'Netherwind Bindings-Wrists',
				'Netherwind Gloves-Hands',
				'Netherwind Belt-Waist',
				'Netherwind Pants-Legs',
				'Netherwind Boots-Feet'
		);
	} // end Mage loot list
	
	if (getQueryVariable("viewselect") == "Paladin") {
		
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Lawbringer Helm',
				'Lawbringer Spaulders',
				'Lawbringer Chestguard',
				'Lawbringer Bracers',
				'Lawbringer Gauntlets',
				'Lawbringer Belt',
				'Lawbringer Legplates',
				'Lawbringer Boots'
		);
		lists['Tier 1'][1]	= new Array(
				'Lawbringer Helm-Head',
				'Lawbringer Spaulders-Shoulders',
				'Lawbringer Chestguard-Chest',
				'Lawbringer Bracers-Wrists',
				'Lawbringer Gauntlets-Hands',
				'Lawbringer Belt-Waist',
				'Lawbringer Legplates-Legs',
				'Lawbringer Boots-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Judgement Crown',
				'Judgement Spaulders',
				'Judgement Breastplate',
				'Judgement Bindings',
				'Judgement Gauntlets',
				'Judgement Belt',
				'Judgement Legplates',
				'Judgement Sabatons'
		);
		lists['Tier 2'][1]	= new Array(
				'Judgement Crown-Head',
				'Judgement Spaulders-Shoulders',
				'Judgement Breastplate-Chest',
				'Judgement Bindings-Wrists',
				'Judgement Gauntlets-Hands',
				'Judgement Belt-Waist',
				'Judgement Legplates-Legs',
				'Judgement Sabatons-Feet'
		);
	} // end Paladin loot list
	
	if (getQueryVariable("viewselect") == "Priest") {
		
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Circlet of Prophecy',
				'Mantle of Prophecy',
				'Robes of Prophecy',
				'Vambraces of Prophecy',
				'Gloves of Prophecy',
				'Girdle of Prophecy',
				'Pants of Prophecy',
				'Boots of Prophecy'
		);
		lists['Tier 1'][1]	= new Array(
				'Circlet of Prophecy-Head',
				'Mantle of Prophecy-Shoulders',
				'Robes of Prophecy-Chest',
				'Vambraces of Prophecy-Wrists',
				'Gloves of Prophecy-Hands',
				'Girdle of Prophecy-Waist',
				'Pants of Prophecy-Legs',
				'Boots of Prophecy-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Halo of Transcendence',
				'Pauldrons of Transcendence',
				'Robes of Transcendence',
				'Bindings of Transcendence',
				'Handguards of Transcendence',
				'Belt of Transcendence',
				'Leggings of Transcendence',
				'Boots of Transcendence'
		);
		lists['Tier 2'][1]	= new Array(
				'Halo of Transcendence-Head',
				'Pauldrons of Transcendence-Shoulders',
				'Robes of Transcendence-Chest',
				'Bindings of Transcendence-Wrists',
				'Handguards of Transcendence-Hands',
				'Belt of Transcendence-Waist',
				'Leggings of Transcendence-Legs',
				'Boots of Transcendence-Feet'
		);
		// Epic quest items
		lists['Epic Quest']		= new Array();
		lists['Epic Quest'][0]	= new Array(
				'The Eye of Divinity'
		);
		lists['Epic Quest'][1]	= new Array(
				'The Eye of Divinity-Epic Quest'
		);
	} // end Priest loot list

	if (getQueryVariable("viewselect") == "Rogue") {

		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Nightslayer Cover',
				'Nightslayer Shoulder Pads',
				'Nightslayer Chestpiece',
				'Nightslayer Bracelets',
				'Nightslayer Gloves',
				'Nightslayer Belt',
				'Nightslayer Pants',
				'Nightslayer Boots'
		); 
		lists['Tier 1'][1]	= new Array(
				'Nightslayer Cover-Head',
				'Nightslayer Shoulder Pads-Shoulders',
				'Nightslayer Chestpiece-Chest',
				'Nightslayer Bracelets-Wrists',
				'Nightslayer Gloves-Hands',
				'Nightslayer Belt-Waist',
				'Nightslayer Pants-Legs',
				'Nightslayer Boots-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Bloodfang Hood',
				'Bloodfang Spaulders',
				'Bloodfang Chestpiece',
				'Bloodfang Bracers',
				'Bloodfang Gloves',
				'Bloodfang Belt',
				'Bloodfang Pants',
				'Bloodfang Boots'
		);
		lists['Tier 2'][1]	= new Array(
				'Bloodfang Hood-Head',
				'Bloodfang Spaulders-Shoulders',
				'Bloodfang Chestpiece-Chest',
				'Bloodfang Bracers-Wrists',
				'Bloodfang Gloves-Hands',
				'Bloodfang Belt-Waist',
				'Bloodfang Pants-Legs',
				'Bloodfang Boots-Feet'
		);
	} // end Rogue loot list
	
	if (getQueryVariable("viewselect") == "Warlock") {
		
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Felheart Horns',
				'Felheart Shoulder Pads',
				'Felheart Robes',
				'Felheart Bracers',
				'Felheart Gloves',
				'Felheart Belt',
				'Felheart Pants',
				'Felheart Slippers'
		);
		lists['Tier 1'][1]	= new Array(
				'Felheart Horns-Head',
				'Felheart Shoulder Pads-Shoulders',
				'Felheart Robes-Chest',
				'Felheart Bracers-Wrists',
				'Felheart Gloves-Hands',
				'Felheart Belt-Waist',
				'Felheart Pants-Legs',
				'Felheart Slippers-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Nemesis Skullcap',
				'Nemesis Spaulders',
				'Nemesis Robes',
				'Nemesis Bracers',
				'Nemesis Gloves',
				'Nemesis Belt',
				'Nemesis Leggings',
				'Nemesis Boots'
		);
		lists['Tier 2'][1]	= new Array(
				'Nemesis Skullcap-Head',
				'Nemesis Spaulders-Shoulders',
				'Nemesis Robes-Chest',
				'Nemesis Bracers-Wrists',
				'Nemesis Gloves-Hands',
				'Nemesis Belt-Waist',
				'Nemesis Leggings-Legs',
				'Nemesis Boots-Feet'
		);
	} // end Warlock loot list
	
	if (getQueryVariable("viewselect") == "Warrior") {
		
		// Tier 1
		lists['Tier 1']		= new Array();
		lists['Tier 1'][0]	= new Array(
				'Helm of Might',
				'Pauldrons of Might',
				'Breastplate of Might',
				'Bracers of Might',
				'Gauntlets of Might',
				'Belt of Might',
				'Legplates of Might',
				'Sabatons of Might'
		);
		lists['Tier 1'][1]	= new Array(
				'Helm of Might-Head',
				'Pauldrons of Might-Shoulders',
				'Breastplate of Might-Chest',
				'Bracers of Might-Wrists',
				'Gauntlets of Might-Hands',
				'Belt of Might-Waist',
				'Legplates of Might-Legs',
				'Sabatons of Might-Feet'
		);
		// Tier 2
		lists['Tier 2']		= new Array();
		lists['Tier 2'][0]	= new Array(
				'Helm of Wrath',
				'Pauldrons of Wrath',
				'Breastplate of Wrath',
				'Bracelets of Wrath',
				'Gauntlets of Wrath',
				'Waistband of Wrath',
				'Legplates of Wrath',
				'Sabatons of Wrath'
		);
		lists['Tier 2'][1]	= new Array(
				'Helm of Wrath-Head',
				'Pauldrons of Wrath-Shoulders',
				'Breastplate of Wrath-Chest',
				'Bracelets of Wrath-Wrists',
				'Gauntlets of Wrath-Hands',
				'Waistband of Wrath-Waist',
				'Legplates of Wrath-Legs',
				'Sabatons of Wrath-Feet'
		);
	} // end Warrior loot list
	
	// Start the listbox thingo...
	
	list = lists[box.options[box.selectedIndex].value];

	// Next empty the slave list

	emptyList( box.form.loottype );

	// Then assign the new list values

	fillList( box.form.loottype, list );

	} //end if 

	if (document.forms['award'].lootname.value == "Tier 1") {
		
		document.forms['award'].dkppaid.value = "50";
			
	}
	
	if (document.forms['award'].lootname.value == "Tier 2") {
		document.forms['award'].dkppaid.value = "60";
	}
	
	if (document.forms['award'].lootname.value == "Epic Quest") { 
		document.forms['award'].dkppaid.value = "50";
	}
	
	if (document.forms['award'].lootname.value == "Other") {
		document.forms['award'].dkppaid.value = "";
	}

}

function change_action() {
	selectform.action = selectform.action + "&viewselect=" +selectform.viewselect.value;
	selectform.submit();
}

function getQueryVariable(variable) {
  var query = window.location.search.substring(1);
  var vars = query.split("&");
  for (var i=0;i<vars.length;i++) {
    var pair = vars[i].split("=");
    if (pair[0] == variable) {
      return pair[1];
    }
  } 
 // alert('Query Variable ' + variable + ' not found');
}

// This function goes through the options for the given
// drop down box and removes them in preparation for
// a new set of values

function emptyList( box ) {
	// Set each option to null thus removing it
	while ( box.options.length ) box.options[0] = null;
}

// This function assigns new drop down options to the given
// drop down box from the list of lists specified

function fillList( box, arr ) {
	// arr[0] holds the display text
	// arr[1] are the values

	for ( i = 0; i < arr[0].length; i++ ) {

		// Create a new drop down option with the
		// display text and value from arr

		option = new Option( arr[0][i], arr[1][i] );

		// Add to the end of the existing options

		box.options[box.length] = option;
	}

	// Preselect option 0

	box.selectedIndex=0;
}

// This function performs a drop down list option change by first
// emptying the existing option list and then assigning a new set

function changeList( box ) {
	// Isolate the appropriate list by using the value
	// of the currently selected option

	list = lists[box.options[box.selectedIndex].value];

	// Next empty the slave list

	emptyList( box.form.slave );

	// Then assign the new list values

	fillList( box.form.slave, list );
}