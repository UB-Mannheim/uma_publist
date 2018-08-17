# uma_publist

[![typoversions](https://img.shields.io/badge/TYPO3-8.7-blue.svg?style=flat.svg)](https://github.com/UB-Mannheim/publist4ubma2#requirements)
[![license](https://img.shields.io/badge/license-GPL%202.0-yellow.svg?style=flat)](https://github.com/UB-Mannheim/publist4ubma2/blob/master/LICENSE)

This is a TYPO3 extension to include publication lists from an EPrints repository
in TYPO3 websites where the lists are generated and synced automatically.

## Features
- filter by creator, title, section, type, year, tag, ...
- sort by year, type and mixtures of both
- optional BibTeX export (linking back to EPrints)
- storing publications in TYPO3-DB
- nightly sync using TYPO3 scheduler
- backend module for manual sync/DB-cleanup
- using extbase + Fluid
- tested with TYPO3 v8.7

## Requirements
- a working TYPO3 CMS
- with the extension [VHS: Fluid ViewHelpers](https://extensions.typo3.org/extension/vhs/)
- needs an [EPrints](http://www.eprints.org) repository with EP3-XML export as source

## Installation

1. Install extension by uploading a zip file of all files from this repository (no additional intermediate folder)
2. Create a new folder (grey icon) in the pagetree (Page) as top element and remember its `id`
3. Go to your start page under `Template` in the `Info/Modify` mode and `Edit the whole template record`
4. Switch to `Includes` tab and import the static template of the extension uma_publist
5. Switch to `General` tab and in the `Constants` editor add the following lines:
```typoscript
plugin.tx_umapublist_pi1 {
        settings {
                # cat=plugin.tx_news/file; type=string; label=Path to CSS file
                cssFile = EXT:uma_publist/Resources/Public/CSS/publist.css
        }

        persistence {
                 # cat=plugin.tx_blogexample//a; type=int+; label=Default storage PID
                storagePid = 5724        }

}
```
6. Replace the number in the `storagePid` with the id of the folder you created in step 2.
7. Possibly you have to clear the cache, log out and in again or something else to trigger the changes.
8. Go in the `Admin Tools` to the uma_publist plugin and `Sync all from Remote`

Afterwards, you can include a new element of type `Plugin` where you specify the plugin type to this extension and use it in your TYPO3 web pages.

## Restriction
The extension is build for the EPrints system of [Mannheim University Library](https://www.bib.uni-mannheim.de/en/),
but it should be possible to modify it for other instances of EPrints.

## Examples
- http://dws.informatik.uni-mannheim.de/en/research/publications/
- http://ms.math.uni-mannheim.de/de/publications/
- https://www.bwl.uni-mannheim.de/forschung/area_banking_finance_and_insurance/articles/
- https://www.bib.uni-mannheim.de/en/publications/

## Contact
In case of question please send an email to sebastian.kotthoff@rz.uni-mannheim.de
