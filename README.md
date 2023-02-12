# Delete Tree Node 


This is a list plugin, which has the functionality to allow the user to delete an item (hierarchy node) by linking the child nodes to the previous node of the deleted, for example:
- **A**
	
	- **B** 
		
		
		-  **C**

By excluding item "B", the link from "C" to "A" is established.

## Use

With the plugin properly installed in Joomla, we can use them in the list plugins, as shown in the image below:
![lista delete](https://user-images.githubusercontent.com/107778190/174809286-590763c5-601a-4dc2-a649-2c6dbd2ce8b4.jpeg)
![WhatsApp Image 2022-06-21 at 11 30 18](https://user-images.githubusercontent.com/107778190/174825325-b190eb17-4f88-44d0-ac71-2c886754ca79.jpeg)

- `Parent Column`: Choose the element (item/column) that will be deleted.

- `Recursively YES`: Responsible for deleting all sub-items from the selected item, that is, it does not link the nodes, it just deletes.

- `Recursively NO`: Responsible for changing the link after deleting the parent item as shown in the example above.
