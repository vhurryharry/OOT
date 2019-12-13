import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ClarityModule } from '@clr/angular';
import { CKEditorModule } from '@ckeditor/ckeditor5-angular';

import { EntitiesComponent } from './entities.component';
import { EntityListComponent } from './entity-list/entity-list.component';
import { CreateEntityComponent } from './create-entity/create-entity.component';

import { EntitiesRoutingModule } from './entities-routing.module';

@NgModule({
  imports: [
    CommonModule,
    EntitiesRoutingModule,
    ClarityModule,
    CKEditorModule,
    FormsModule,
    ReactiveFormsModule
  ],
  declarations: [EntitiesComponent, EntityListComponent, CreateEntityComponent]
})
export class EntitiesModule {}
