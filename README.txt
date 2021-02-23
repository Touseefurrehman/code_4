There were so many flaws in the given code . Tried to remove most of them .
But still i can do alot of improvements in given code to make it more efficient and reusable

Used Jobs or Event Listener for sending email
Excessive use of checks has been removed
Excessive use of arrays have been reduced which are making code terrible
Laravel validation should be used which can reduce alot of unnecessary checks
Booking repository has extra methods and code . Mostly methods are not used in controller
So many useless variables are declare in code and fetched queries as well , those are useless for that method
Excessive use of functions ,familiar functionalities can be performed by single method

I should recommend to use services for business logic ,
repository of DB purpose e.g (queries , posting data)
and use controllers for (validating , authentication and re-routing)

This approach or pattern can divide code into minimum chunks which would easy to reuse and flexible for enhancing functionality
