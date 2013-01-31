//
// west warehouse wh_product
// 
function West_Warehouse_Wh_Product() {
	
	//
	// controller section
	//
	
	// init
	this.init = function() {
		// here we construct ui
		var config = new Object({
			collapsible: false,
			title: 'Produkty',
			titleCollapse: false,
			tools: [
			    { id:'save', qtip: 'Dodaj Produkt', handler: this.directory_add },
			    { id:'refresh', qtip: 'Odśwież', handler: this.refresh }
			]
		});
		this.ui = new Panel();
		this.ui.init(config);
		//
		//
		// here we run fixed elements for section
		//
		//
		var config = new Object({
            controller: 'wh:',
			dataUrl: base_url+'/_system:tree/tree_create/wh_product',
			ddGroup: 'product',
            //ddGroupExtended: 'structure_website',
			id: 'product'
		});
		this.ui_tree = new Tree();
		this.ui_tree.init(config);
		new Helper_Ui().add_ui(this.ui.display, this.ui_tree.display);
	}
	
	// directory_add
	this.directory_add = function() {
		new Helper_Ui().add_window('wh_product_directory_add', 'Dodaj katalog', base_url +'/_system:tree/add/wh:product,0');
	}
	
	// refresh content
	this.refresh = function() {
		west_warehouse_wh_product.ui_tree.root.reload();
		west_warehouse_wh_product.ui_tree.path_expand();
	}
	
}