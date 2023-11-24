-- This example builds a HTML table with the results of a SPARQL query.
-- By default one column is used per SPARQL variable.
-- If the second parameter is set to "true", the first two columns are combined into one column.

local sparql = require('SPARQL')
local mwHtml = require('mw.html')

local p = {}

function p.buildTableFromSparql(frame)
	local sparqlQuery = frame.args[1]
	local combineFirstTwoColumns = trimAndLower(frame.args[2]) == "true"
	-- PHP function sparql.runQuery(query) is called
	local jsonResults = sparql.runQuery(sparqlQuery)
	local headers = {}
	if jsonResults and jsonResults.head and jsonResults.head.vars then
		headers = jsonResults.head.vars
	end
	local dataTable = convertJsonToTable(jsonResults)
	return createHtmlTable(dataTable, headers, combineFirstTwoColumns)
end

function trimAndLower(str)
	if str == nil then return nil end
	str = str:gsub("^%s*(.-)%s*$", "%1")  -- Trim spaces from both ends
	return str:lower()  -- Convert to lowercase
end

function convertJsonToTable(jsonResults)
	local resultsTable = {}
	if jsonResults and jsonResults.results and jsonResults.results.bindings then
		local bindings = jsonResults.results.bindings
		for j=0, #bindings do
			local row = {}
			for key, value in pairs(bindings[j]) do
				table.insert(row, value.value)
			end
			table.insert(resultsTable, row)
		end
	end
	return resultsTable
end

function createHtmlTable(dataTable, headers, combineFirstTwoColumns)
	local htmlTable = mwHtml.create('table')
	htmlTable:addClass('wikitable'):attr('border', '1')

	if combineFirstTwoColumns and #headers > 1 then
		local headerRow = htmlTable:tag('tr')
		headerRow:tag('th'):wikitext(headers[0] .. " + " .. headers[1])
		for j = 2, #headers do
			headerRow:tag('th'):wikitext(headers[j])
		end
		for _, row in ipairs(dataTable) do
			local dataRow = htmlTable:tag('tr')
			local combinedData = '[' .. row[1] .. ' ' .. row[2] .. ']'
			dataRow:tag('td'):wikitext(combinedData)
			for j = 3, #row do
				dataRow:tag('td'):wikitext(row[j])
			end
		end
	else
		if #headers > 1 then
			local headerRow = htmlTable:tag('tr')
			for j = 0, #headers do
				headerRow:tag('th'):wikitext(headers[j])
			end
		end
		for _, row in ipairs(dataTable) do
			local dataRow = htmlTable:tag('tr')
			for _, data in ipairs(row) do
				dataRow:tag('td'):wikitext(data)
			end
		end
	end

	return tostring(htmlTable)
end

return p
