/**
 * Initial dataset pre-populated from Xixify × DevPify Partnership Accounts Google Sheet.
 */
const INITIAL_DATA = {
  settings: {
    agencyName: "Xixify × DevPify",
    currency: "BDT",
    partners: [
      { name: "Sumayah Islam", split: 50 },
      { name: "Firoz Mahamud", split: 50 }
    ]
  },
  projects: [
    {
      id: "proj-1",
      name: "Canopy (Jan Salary)",
      client: "Canopy",
      source: "Salary",
      leadOwner: "Sumayah",
      amount: 248000,
      expenses: 0,
      paid: 248000,
      due: 0,
      month: "January",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t1", title: "January Development & Maintenance", status: "Completed" }
      ]
    },
    {
      id: "proj-2",
      name: "Twills Original",
      client: "Twills",
      source: "Rifat",
      leadOwner: "Sumayah",
      amount: 40000,
      expenses: 0,
      paid: 30000,
      due: 10000,
      month: "January",
      status: "Partial",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t2", title: "Theme Customization", status: "Completed" },
        { id: "t3", title: "Final Payment Settlement", status: "In Progress" }
      ]
    },
    {
      id: "proj-3",
      name: "ZUQO",
      client: "ZUQO",
      source: "Tarikul Islam",
      leadOwner: "Sumayah",
      amount: 6000,
      expenses: 0,
      paid: 6000,
      due: 0,
      month: "January",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t4", title: "Website Bug Fixes", status: "Completed" }
      ]
    },
    {
      id: "proj-4",
      name: "Car4mates",
      client: "Car4mates",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 9000,
      expenses: 0,
      paid: 9000,
      due: 0,
      month: "January",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t5", title: "Landing Page Development", status: "Completed" }
      ]
    },
    {
      id: "proj-5",
      name: "DoelCell",
      client: "DoelCell",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 15000,
      expenses: 0,
      paid: 15000,
      due: 0,
      month: "January",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t6", title: "E-Commerce Setup", status: "Completed" }
      ]
    },
    {
      id: "proj-6",
      name: "MarajMedia",
      client: "MarajMedia",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 15000,
      expenses: 0,
      paid: 15000,
      due: 0,
      month: "January",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t7", title: "Agency Website Build", status: "Completed" }
      ]
    },
    {
      id: "proj-7",
      name: "Property",
      client: "Property Client",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 0,
      expenses: 0,
      paid: 0,
      due: 0,
      month: "January",
      status: "Pending",
      distributed: "No",
      clientVisible: false,
      tasks: [
        { id: "t8", title: "Requirement Gathering", status: "Pending" }
      ]
    },
    {
      id: "proj-8",
      name: "Thank You Page",
      client: "Thank You Page",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 3500,
      expenses: 0,
      paid: 0,
      due: 3500,
      month: "January",
      status: "Pending",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t9", title: "Design & Copywriting", status: "In Progress" }
      ]
    },
    {
      id: "proj-9",
      name: "Head Warrior",
      client: "Head Warrior",
      source: "Sumayah",
      leadOwner: "Sumayah",
      amount: 12400,
      expenses: 0,
      paid: 0,
      due: 12400,
      month: "February",
      status: "Pending",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t10", title: "Branding & Web App Scaffold", status: "Pending" }
      ]
    },
    {
      id: "proj-10",
      name: "Aion Studio",
      client: "Aion Studio",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 15000,
      expenses: 0,
      paid: 7500,
      due: 7500,
      month: "February",
      status: "Partial",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t11", title: "UI Mockup & Prototyping", status: "Completed" },
        { id: "t12", title: "WordPress Integration", status: "In Progress" }
      ]
    },
    {
      id: "proj-11",
      name: "Canopy (Feb Salary)",
      client: "Canopy",
      source: "Salary",
      leadOwner: "Sumayah",
      amount: 248000,
      expenses: 0,
      paid: 248000,
      due: 0,
      month: "February",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t13", title: "February Deliverables", status: "Completed" }
      ]
    },
    {
      id: "proj-12",
      name: "Lab Creation",
      client: "Lab Creation",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 35000,
      expenses: 0,
      paid: 35000,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t14", title: "Custom Web Application", status: "Completed" }
      ]
    },
    {
      id: "proj-13",
      name: "SAMAF",
      client: "SAMAF",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 3650,
      expenses: 0,
      paid: 3650,
      due: 0,
      month: "February",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t15", title: "Site Fixes & Deployment", status: "Completed" }
      ]
    },
    {
      id: "proj-14",
      name: "Gemglow Landing Page",
      client: "Gemglow",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 5000,
      expenses: 0,
      paid: 5000,
      due: 0,
      month: "February",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t16", title: "Landing Page Design", status: "Completed" }
      ]
    },
    {
      id: "proj-15",
      name: "thepush.agency",
      client: "thepush.agency",
      source: "Walid",
      leadOwner: "Sumayah",
      amount: 17892,
      expenses: 0,
      paid: 0,
      due: 17892,
      month: "March",
      status: "Paid",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t17", title: "Agency Site Build", status: "Completed" }
      ]
    },
    {
      id: "proj-16",
      name: "Voltas' Spa",
      client: "Voltas' Spa",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 30000,
      expenses: 0,
      paid: 15000,
      due: 15000,
      month: "March",
      status: "Partial",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t18", title: "Booking Integration", status: "In Progress" }
      ]
    },
    {
      id: "proj-17",
      name: "Claud Tools (Expense)",
      client: "Internal Operations",
      source: "Firoz",
      leadOwner: "Firoz",
      amount: 0,
      expenses: 2454,
      paid: 0,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "No",
      clientVisible: false,
      tasks: [
        { id: "t19", title: "Claude AI License Renewal", status: "Completed" }
      ]
    },
    {
      id: "proj-18",
      name: "Tax Support",
      client: "Tax Client",
      source: "Firoz",
      leadOwner: "Firoz",
      amount: 49000,
      expenses: 0,
      paid: 49000,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t20", title: "Tax Automation System", status: "Completed" }
      ]
    },
    {
      id: "proj-19",
      name: "Apu Bhiya CM 20%",
      client: "Apu Bhiya",
      source: "Apu",
      leadOwner: "Sumayah",
      amount: 12260,
      expenses: 0,
      paid: 12260,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t21", title: "Content Management Module", status: "Completed" }
      ]
    },
    {
      id: "proj-20",
      name: "Simpli Basic Monthly",
      client: "Simpli Basic",
      source: "Fayaz Bhai",
      leadOwner: "Sumayah",
      amount: 19000,
      expenses: 0,
      paid: 19000,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t22", title: "Monthly Retainer Tasks", status: "Completed" }
      ]
    },
    {
      id: "proj-21",
      name: "Canopy (March Salary)",
      client: "Canopy",
      source: "Salary",
      leadOwner: "Sumayah",
      amount: 248000,
      expenses: 0,
      paid: 248000,
      due: 0,
      month: "March",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t23", title: "March Sprint Deliverables", status: "Completed" }
      ]
    },
    {
      id: "proj-22",
      name: "Canopy (April Salary)",
      client: "Canopy",
      source: "Salary",
      leadOwner: "Sumayah",
      amount: 248000,
      expenses: 0,
      paid: 248000,
      due: 0,
      month: "April",
      status: "Paid",
      distributed: "Yes",
      clientVisible: true,
      tasks: [
        { id: "t24", title: "April Sprint Deliverables", status: "Completed" }
      ]
    },
    {
      id: "proj-23",
      name: "Hosting for 4 Years (Expense)",
      client: "Internal Infrastructure",
      source: "Infrastructure",
      leadOwner: "Sumayah",
      amount: 0,
      expenses: 14266,
      paid: 0,
      due: 0,
      month: "May",
      status: "Paid",
      distributed: "No",
      clientVisible: false,
      tasks: [
        { id: "t25", title: "4-Year Server & Domain Plan", status: "Completed" }
      ]
    },
    {
      id: "proj-24",
      name: "MKPmc",
      client: "MKPmc",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 35000,
      expenses: 0,
      paid: 35000,
      due: 0,
      month: "May",
      status: "Paid",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t26", title: "Medical Portal Customization", status: "Completed" }
      ]
    },
    {
      id: "proj-25",
      name: "QII",
      client: "QII",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 30000,
      expenses: 0,
      paid: 30000,
      due: 0,
      month: "July",
      status: "Paid",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t27", title: "Platform Architecture", status: "Completed" }
      ]
    },
    {
      id: "proj-26",
      name: "TSM",
      client: "TSM UK",
      source: "Anzar UK",
      leadOwner: "Sumayah",
      amount: 42000,
      expenses: 0,
      paid: 42000,
      due: 0,
      month: "July",
      status: "Paid",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t28", title: "WordPress E-Commerce Portal", status: "Completed" }
      ]
    },
    {
      id: "proj-27",
      name: "Arman Website Transfer",
      client: "Arman",
      source: "Abrar",
      leadOwner: "Sumayah",
      amount: 30000,
      expenses: 10000,
      paid: 0,
      due: 30000,
      month: "July",
      status: "Pending",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t29", title: "Database & File Server Migration", status: "In Progress" }
      ]
    },
    {
      id: "proj-28",
      name: "Painting Site",
      client: "Painting Client",
      source: "Fayaz",
      leadOwner: "Sumayah",
      amount: 7000,
      expenses: 0,
      paid: 0,
      due: 7000,
      month: "July",
      status: "Pending",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t30", title: "Portfolio Gallery Setup", status: "In Progress" }
      ]
    },
    {
      id: "proj-29",
      name: "Comete",
      client: "Comete",
      source: "Zahid",
      leadOwner: "Sumayah",
      amount: 24000,
      expenses: 0,
      paid: 0,
      due: 24000,
      month: "July",
      status: "Pending",
      distributed: "No",
      clientVisible: true,
      tasks: [
        { id: "t31", title: "SaaS Dashboard Prototype", status: "In Progress" }
      ]
    }
  ]
};
