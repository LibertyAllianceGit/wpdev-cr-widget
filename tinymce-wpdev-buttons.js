(function() {
    tinymce.PluginManager.add( 'wpdev_class', function( editor, url ) {
        // Add Button to Visual Editor Toolbar
        editor.addButton('wpdev_class', {
            title: 'Insert CR Score',
            cmd: 'wpdev_class',
            image: url + '/cr-icon.png',
        });
 
        // Add Command when Button Clicked
        editor.addCommand('wpdev_class', function() {
            // Check we have selected some text selected
            var text = editor.selection.getContent({
                'format': 'html'
            });
            if ( text.length === 0 ) {
                alert( 'Please select some text.' );
                return;
            }

            // Ask the user to enter a CSS class
            var result = '1';
            if ( !result ) {
                // User cancelled - exit
                return;
            }
            if (result.length === 0) {
                // User didn't enter anything - exit
                return;
            }

            // Insert selected text back into editor, wrapping it in an anchor tag
            editor.execCommand('mceReplaceContent', false, '[score]' + text + '[/score]');
        });

        // Enable/disable the button on the node change event
        editor.onNodeChange.add(function( editor ) {
            // Get selected text, and assume we'll disable our button
            var selection = editor.selection.getContent();
            var disable = true;

            // If we have some text selected, don't disable the button
            if ( selection ) {
                disable = false;
            }

            // Define whether our button should be enabled or disabled
            editor.controlManager.setDisabled( 'wpdev_class', disable );
        });
    });
})();