local sparql = require('SPARQL')

local p = {}

function p.visualizeSparqlResults(frame)
	local sparqlQuery = frame.args[1]
	local queryResults = sparql.runQuery(sparqlQuery)

	return '<pre>' .. visualizeValue(queryResults) .. '</pre>'
end

function visualizeValue(value, depth)
	depth = depth or 0
	local indent = string.rep("  ", depth)

	local output = {}
	for k, v in pairs(value) do
		if type(v) == "table" then
			table.insert(output, indent .. k .. ":")
			table.insert(output, visualizeValue(v, depth + 1))
		else
			table.insert(output, indent .. k .. ": " .. tostring(v))
		end
	end

	return table.concat(output, "\n")
end

return p
