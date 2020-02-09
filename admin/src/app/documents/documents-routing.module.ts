import { NgModule } from "@angular/core";
import { RouterModule, Routes } from "@angular/router";

import { DocumentsComponent } from "./documents.component";
import { DocumentListComponent } from "./document-list/document-list.component";
import { CreateDocumentComponent } from "./create-document/create-document.component";

const routes: Routes = [
  {
    path: "",
    component: DocumentsComponent,
    children: [
      {
        path: "edit/:id",
        component: CreateDocumentComponent
      },
      {
        path: "",
        component: DocumentListComponent
      }
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class DocumentsRoutingModule {}
