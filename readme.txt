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

prescription
medication administration

dashboard link

unavailable
reason

light mode/dark mode

---

SCOPE:
Main Functions
- Add, View, Update, Delete, Search - Patient Info
- Add, View, Update, Delete, Search - Medicine Type and Brand
- Add, View, Update, Delete, Search - Medicine Details
- Add, View, Update, Delete, Search - Equipment and its Brand
- Add, View, Update, Delete, Search - Equipment Details
- Add, View, Update, Delete - Clinic Attendant
- Add - Medication (formerly Prescription)
- View - Patient Medication History

Summary of Records
- Total patients of the current day
- Total patients of the current week
- Total patients of the current month
- Total patients of the current year

- Total medicine stocks
- Medicine to be expired in 1 month
- Medicine need to be restocked
- Total quantity of expired medicines

- Total available equipments
- Total defective equipments
- Recently added equipment
- Recently removed equipment

- Total clinic attendants
- Total medicine brands
- Date of soonest upcoming visit
- Total deleted items in the system

Recovery System
- Patient Info
- Medicine Type and Brand
- Medicine Details
- Equipment and its Brand
- Equipment Details

Generating Reports
- Records of Patient Visits Between Two Dates
- Records of Disease/Illness-based Report Between Two Dates

Security System
- Login with username and password

Accessibility
- Accessible offline when hosted locally
- Compatible and responsive to mobile devices

LIMITATIONS:
- Recommeded OS to use: Windows 7, 8, 10, Android, Mac or iOS
- Recommeded updated browsers to use: Chrome, Firefox, MS Edge, Safari, Opera, and Brave
- Wirelessly accessible only through Internet-capable mobile devices
- System access levels are only for Clinic Attendant role/position
- Exclusive for SPCC Caloocan


ADD EQUIPMENT	
[text] equipment
[text] brand
[date] date acquired
[number] total quantity

ADD BORROWER
[text] fname
[text] lname
[text] position
[text] position_id

ADD EQUIPMENT DETAILS (List Form)
[select] equipment-brand
[select] status
[select] state *if 'Unavailable', enable dates
[date] *unavailable_until
[date] *unavailable_since
[number] qty
[textarea] remarks

BORROWING (List form)
[select] last name - id
[select] available equipment-brand
[number] qty

--- UPDATE DATABASE ---

TBL_EQUIPMENT
id	equipment	brand	date_acquired	total_qty

TBL_BORROWER
id	fname	lname	position	position_id

TBL_AVAILABILITY
id	equipment_id	status	unavailable_since	unavailable_until	state	qty	remarks

TBL_BORROWED	
id	borrower_id	availability_id
