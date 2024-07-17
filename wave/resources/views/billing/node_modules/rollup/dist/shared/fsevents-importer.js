/*
  @license
	Rollup.js v4.18.1
	Mon, 08 Jul 2024 15:24:39 GMT - commit 21f9a4949358b60801c948cd4777d7a39d9e6de0

	https://github.com/rollup/rollup

	Released under the MIT License.
*/
'use strict';

let fsEvents;
let fsEventsImportError;
async function loadFsEvents() {
    try {
        ({ default: fsEvents } = await import('fsevents'));
    }
    catch (error) {
        fsEventsImportError = error;
    }
}
// A call to this function will be injected into the chokidar code
function getFsEvents() {
    if (fsEventsImportError)
        throw fsEventsImportError;
    return fsEvents;
}

const fseventsImporter = /*#__PURE__*/Object.defineProperty({
    __proto__: null,
    getFsEvents,
    loadFsEvents
}, Symbol.toStringTag, { value: 'Module' });

exports.fseventsImporter = fseventsImporter;
exports.loadFsEvents = loadFsEvents;
//# sourceMappingURL=fsevents-importer.js.map
