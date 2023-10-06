<?php

namespace App\Filament\Resources;

use App\Enums\ProductTypeEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Forms\Form;
use Psy\Util\Str;
use Random\Engine\Secure;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Products';



    //for groping related products together
    protected static ?string $navigationGroup = "Shop";

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()->schema([
                        TextInput::make('name')
                        ->required()
                        ->live(true)
                            ->unique()
                            ->afterStateUpdated(function(string $operation, $state, Forms\Set $set){
                                if($operation !== 'create'){
                                    return ;
                                }

                                $set('slug', \Illuminate\Support\Str::slug($state));
                            }),
//                        Making a slug
                        TextInput::make('slug')
                        ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Product::class, 'slug', ignoreRecord: true),

//                        This is Description
                        MarkdownEditor::make('description')->columnSpan(2),
                    ])->columns(2),
                    Section::make('Pricing & Inventory')->schema([
//                        stock keeping unit, unique id for products
                        TextInput::make('sku')->label('SKU, (Stock keeping unit)')->unique()->required(),
                        TextInput::make('price')->numeric()->rules(['regex: /^\d{1,6}(\.\d{0,2})?$/'])->required(),
                        TextInput::make('quantity')->rules(['integer','min:0',"max:100"]),
                        Select::make('type')->options([
                            'downloadable'=> ProductTypeEnum::DOWNLOADABLE->value,
                            'deliverable'=>ProductTypeEnum::DELIVERABLE->value,
                        ])
                    ])->columns(2)
                    ]),

                    Group::make()->schema([
                        Section::make('Status')->schema([
                            Toggle::make('is_visible')->label('visibility')->helperText('Enable or disable product visibility')->default(true),
                            Toggle::make('is_featured')->label('Features')->helperText('Is the product featured'),
                            DatePicker::make('publish_at')->label('Availability')->default(now()),
                        ])->columns(2),

                        Section::make('Image')->schema([
                            FileUpload::make('image')->directory('form-attachments')->preserveFilenames()->image()->imageEditor()
                        ])->collapsible(),


                        Section::make('Associations')->schema([
                            Select::make('brand_id')->relationship('brand', 'name')
                        ])->collapsible()
                        ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image'),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('brand.name')->searchable()->sortable()->toggleable(),
                IconColumn::make('is_visible')->boolean()->sortable()->toggleable()->label('visibility'),
                TextColumn::make('price')->toggleable()->sortable(),
                TextColumn::make('quantity'),
                TextColumn::make('publish_at')->label('Availability')->date()->sortable(),
                TextColumn::make('type'),
            ])
            ->filters([
                //making a filter
                Tables\Filters\TernaryFilter::make('is_visible')->label('Visibility')
                    ->trueLabel('Only visible Products')
                    ->falseLabel('Only Hidden Products')
                    ->native(false),


                // Select Filters
                Tables\Filters\SelectFilter::make('brand')->relationship('brand', 'name')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
