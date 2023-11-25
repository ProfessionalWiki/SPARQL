local sparql = require('SPARQL') -- Load the SPARQL binding

local p = {}

function p.showFirstValue(frame)
	local sparqlQuery = frame.args[1]
	local queryResults = sparql.runQuery(sparqlQuery) -- Use the runQuery method

	-- nil is returned if Blazegraph did not return a valid response
	if queryResults == nil then
		return ''
	end

	-- Replace "work" with the first SELECT variable in your SPARQL query
	return queryResults['results']['bindings'][0]['work']['value']
end

return p
