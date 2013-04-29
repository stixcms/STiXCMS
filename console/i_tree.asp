<%
	
	Sub print_tree(byVal id,byVal param,byVal history) 
		if history<>"" then h=split(history,",")
		response.write "<table border=0 cellspacing=0 cellpadding=3>" & VBCRLF
		t=sm.getSubNodes(id,param)

		For i=0 To UBoundFullO(t)
			if isObject(t(i)) Then
				response.write "<tr><td background=../common/img/node.gif><a href=" & script_name 
				If history<>"" then
					For j=LBound(h) To UboundFull(h): If h(j)<>"" Then response.write "exp[]=" & h(j) & "&" : End If :Next 
				End If
				img="plus"
				If Not IsEmpty(expd) Then if in_array(t(i).node_id,expd) Then img="minus"
				response.write "exp[]=" & (t(i).node_id) & "&nid=" & (t(i).node_id) & "&nparam=" & param & "><img src=../common/img/" & img & ".gif width=9 height=9 border=0></a>&nbsp;<a href=" & chr(34) & "Javascript:show(" & t(i).node_id & "," & param & ",'" & t(i).node_name & "');" & chr(34) & ">" & t(i).node_name & "</a>"
				If img="minus" Then print_tree t(i).node_id,param,t(i).node_id & "," & history
				response.write "</td></tr>" & VBCRLF
			End If
		Next 
		response.write "</table>" & VBCRLF
	End Sub
%>