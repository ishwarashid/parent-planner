# Parent Planner - Child Management Module

## Parenting Schedule

During the implementation of the expanded child information module, a decision was made regarding the "Parenting Schedule" field.

The initial request was to add a field to capture the parenting schedule or custody arrangement. However, upon reviewing the existing functionality, it was determined that the "Visitation" module already provides a comprehensive way to manage and track parenting schedules.

Therefore, to avoid redundancy and keep the data model clean, we have opted to use the existing **Visitation module** for managing parenting schedules instead of adding a new field to the child's profile. This approach allows for more detailed and flexible scheduling than a simple text field would provide.
