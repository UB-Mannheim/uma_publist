# Publist4ubma2

[![typoversions](https://img.shields.io/badge/TYPO3-6.2,%207.6-blue.svg?style=flat.svg)](https://github.com/UB-Mannheim/publist4ubma2#requirements)
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

## Requirements
- tested with TYPO3 v6.2 and v7.6
- needs an [EPrints](http://www.eprints.org) repository with EP3-XML export as source 

## Restriction
The extension is build for the EPrints system from library of university of Mannheim.
But it should be possible to modify it for other instances of EPrints

## Examples
- http://dws.informatik.uni-mannheim.de/en/research/publications/
- http://ms.math.uni-mannheim.de/de/publications/
- https://www.bwl.uni-mannheim.de/forschung/area_banking_finance_and_insurance/articles/
- http://www.bib.uni-mannheim.de/publikationen

## Contact
In case of question please send an email to sebastian.kotthoff@rz.uni-mannheim.de
