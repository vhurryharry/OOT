import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { EntitiesComponent } from "./entities.component";
import { EntityListComponent } from "./entity-list/entity-list.component";
import { CreateEntityComponent } from "./create-entity/create-entity.component";

const routes: Routes = [
  {
    path: "",
    component: EntitiesComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateEntityComponent
      },
      {
        path: "",
        component: EntityListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class EntitiesRoutingModule {}
