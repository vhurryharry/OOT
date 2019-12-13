import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { DocumentsComponent } from './documents.component';
import { DocumentListComponent } from './document-list/document-list.component';
import { CreateDocumentComponent } from './create-document/create-document.component';

import { DocumentsRoutingModule } from './documents-routing.module';

@NgModule({
  imports: [
    CommonModule,
    DocumentsRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [
    DocumentsComponent,
    DocumentListComponent,
    CreateDocumentComponent
  ]
})
export class DocumentsModule {}
