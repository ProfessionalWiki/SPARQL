local sparql = {}
local php

function sparql.setupInterface( options )
	sparql.setupInterface = nil
	php = mw_interface
	mw_interface = nil

	package.loaded['SPARQL'] = sparql
end

function sparql.runQuery( expression )
	return php.runQuery( expression )
end

return sparql
