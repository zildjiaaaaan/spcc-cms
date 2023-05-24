prescription remarks (save) • 
prescription remarks (view) •

Med Expiry Date •
Med Brand • 
Med unit (change to dropdown, should have own table) •
med quantity •

fix patient history table •
quantity should decrease •
clinic equipments •
dashboard •

activity log

emergency contact person •
relationship •
emergency contact no •

recovery system
>medicine details •
>equipment •
>equipment details •

r2Rx@FmaEISXQE@h!5oI
TyIZZe*H*$VgaTnvrvO!

--------------------------

student id duplicate handler •

prescription -> medication administration •

dashboard link •

unavailable
reason

light mode/dark mode

---

ADD EQUIPMENT	
[text] equipment
[text] brand
[date] date acquired
[number] total quantity

ADD BORROWER
[text] fname
[text] mname
[text] lname
[text] position
[text] position_id
[number] contact

ADD EQUIPMENT DETAILS (List Form)
[select] equipment-brand
[select] status *if 'Unavailable', enable dates
[select] state *if 'Borrowed', enable dates
[select] *borrower
[date] *unavailable_until
[date] *unavailable_since
[number] qty
[textarea] remarks

BORROWING (List form)
[select] last name - id
[select] available equipment-brand
[number] qty

--- UPDATE DATABASE ---

TBL_EQUIPMENT •
id	equipment	brand	date_acquired	total_qty

TBL_BORROWER •
id	fname	lname	position	position_id	contact_no

TBL_AVAILABILITY 
id	equipment_id	status	unavailable_since	unavailable_until	state	qty	remarks

TBL_BORROWED	
id	borrower_id	availability_id
