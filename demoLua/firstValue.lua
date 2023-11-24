local sparql = require('SPARQL')

local p = {}

function p.showFirstValue(frame)
	local sparqlQuery = frame.args[1]
	local queryResults = sparql.runQuery(sparqlQuery)

	-- Replace "work" with the first SELECT variable in your SPARQL query
	return queryResults['results']['bindings'][1]['work']['value']
end

return p
