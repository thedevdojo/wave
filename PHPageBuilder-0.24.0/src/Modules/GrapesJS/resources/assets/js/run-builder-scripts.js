(function() {

    window.customBuilderScripts = {};

    /**
     * On instantiating the component model, before it is mounted in the canvas.
     */
    window.editor.on('component:create', component => {
        // extract the script tag of the given component (if it has one)
        if (component.components().length) {
            let lastChild = component.components().models[component.components().length - 1];
            if (lastChild.attributes.type === 'script') {
                let blockId = component.attributes.attributes['block-id'];
                window.customBuilderScripts[blockId] = lastChild.toHTML();
                lastChild.remove();
            }
        }
    });

    /**
     * After mounting the component in the canvas.
     */
    window.editor.on('component:add', function (component) {
        // run the script that was set when creating this component
        if (component.attributes['run-builder-script'] !== undefined) {
            let originalCustomBuilderScripts = customBuilderScripts;

            window.customBuilderScripts[component.attributes['block-id']] = customBuilderScripts[component.attributes['run-builder-script']];
            runScriptsOfComponentAndChildren(component);

            window.customBuilderScripts = originalCustomBuilderScripts;
            delete component.attributes['run-builder-script'];
        }
    });

    /**
     * On ending a component order drag, re-run builder scripts on newly added HTMl.
     */
    window.editor.on('sorter:drag:end', function(event) {
        let component = event.modelToDrop;
        if (component && component.attributes && component.attributes['block-id']) {
            window.runScriptsOfComponentAndChildren(component);
        }
    });

    /**
     * Run the custom builder scripts of the given component and of all child components.
     *
     * @param component
     */
    window.runScriptsOfComponentAndChildren = function(component) {
        runComponentScript(component);
        component.components().each(function(child) {
            runScriptsOfComponentAndChildren(child);
        });
    }

    /**
     * Run the custom builder scripts of the given component.
     *
     * @param component
     */
    function runComponentScript(component) {
        let blockId = component.attributes['block-id'];
        if (blockId && window.customBuilderScripts[blockId] !== undefined) {
            let styleIdentifier = component.attributes["style-identifier"];
            let $scriptTag = $("<container>").append(window.customBuilderScripts[blockId]);
            // prepend block and blockSelector variables allowing the script to refer to this exact block instance
            $scriptTag.find('script').prepend('let inPageBuilder = true;');
            $scriptTag.find('script').prepend('let blockSelector = ".' + styleIdentifier + '";');
            $scriptTag.find('script').prepend('let block = document.getElementsByClassName("' + styleIdentifier + '")[0];');
            // wrap the script contents in a self-invoking function (to add a scope avoiding variable name collisions)
            $scriptTag.find('script').prepend('(function(){');
            $scriptTag.find('script').append('})();');

            // execute the script in the page that is being edited
            let scriptTag = document.createElement("script");
            scriptTag.type = "text/javascript";
            scriptTag.innerHTML = $scriptTag.find('script').html();
            window.editor.Canvas.getDocument().body.appendChild(scriptTag);
        }
    }

})();
